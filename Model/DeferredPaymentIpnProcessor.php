<?php
/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Hokodo\BNPL\Model;

use Hokodo\BNPL\Api\Data\DeferredPaymentIpnPayloadInterface;
use Hokodo\BNPL\Api\Data\OrderIpnInterface;
use Hokodo\BNPL\Api\DeferredPaymentIpnProcessorInterface;
use Hokodo\BNPL\Gateway\Service\DifferedPayment\OrderProcessor;
use Hokodo\BNPL\Gateway\Service\DifferedPayment\PaymentProcessor;
use Hokodo\BNPL\Model\Data\OrderIpnFactory;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Exception\LocalizedException;
use Magento\Sales\Model\OrderFactory;
use Psr\Log\LoggerInterface;

class DeferredPaymentIpnProcessor implements DeferredPaymentIpnProcessorInterface
{
    /**
     * @var OrderFactory
     */
    private OrderFactory $orderFactory;

    /**
     * @var DataObjectHelper
     */
    private DataObjectHelper $dataObjectHelper;

    /**
     * @var OrderIpnFactory
     */
    private OrderIpnFactory $orderIpnFactory;

    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * @var OrderProcessor
     */
    private OrderProcessor $orderProcessor;

    /**
     * @var PaymentProcessor
     */
    private PaymentProcessor $paymentProcessor;

    /**
     * @param OrderFactory     $orderFactory
     * @param OrderIpnFactory  $orderIpnFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param OrderProcessor   $orderProcessor
     * @param PaymentProcessor $paymentProcessor
     * @param LoggerInterface  $logger
     */
    public function __construct(
        OrderFactory $orderFactory,
        OrderIpnFactory $orderIpnFactory,
        DataObjectHelper $dataObjectHelper,
        OrderProcessor $orderProcessor,
        PaymentProcessor $paymentProcessor,
        LoggerInterface $logger
    ) {
        $this->orderFactory = $orderFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->orderIpnFactory = $orderIpnFactory;
        $this->orderProcessor = $orderProcessor;
        $this->paymentProcessor = $paymentProcessor;
        $this->logger = $logger;
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\DeferredPaymentIpnProcessorInterface::process()
     */
    public function process($created, DeferredPaymentIpnPayloadInterface $data)
    {
        try {
            $result = false;
            $ipnOrder = $this->orderIpnFactory->create();
            $this->dataObjectHelper->populateWithArray(
                $ipnOrder,
                $data->getOrder(),
                \Hokodo\BNPL\Api\Data\OrderIpnInterface::class
            );
            if ($ipnOrder && $ipnOrder->getDeferredPayment()) {
                $result = $this->processIpnOrder($ipnOrder);
            }
            return $result;
        } catch (\Exception $e) {
            $data = [
                'message' => 'Hokodo_BNPL: Webhook error with order - ' . $ipnOrder->getId(),
                'error' => $e->getMessage(),
            ];
            $this->logger->error(__METHOD__, $data);
        }
    }

    /**
     * A function that make process ipn order.
     *
     * @param OrderIpnInterface $ipnOrder
     *
     * @return bool
     *
     * @throws LocalizedException
     */
    private function processIpnOrder(OrderIpnInterface $ipnOrder): bool
    {
        $order = $this->orderFactory->create()->loadByAttribute('increment_id', $ipnOrder->getUniqueId());
        if ($order->getId()
            && $order->getPayment()->getAdditionalInformation()['hokodo_order_id'] === $ipnOrder->getId()) {
            $deferredPayment = $ipnOrder->getDeferredPayment();
            $payment = $order->getPayment();
            $status = $deferredPayment->getStatus();
            $transactionId = $deferredPayment->getNumber();
            $this->orderProcessor->process($order, $status);
            $this->paymentProcessor->process($payment, $status, $transactionId, true);
            return true;
        }
        return false;
    }
}
