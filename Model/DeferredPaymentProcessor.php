<?php
/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Hokodo\BNPL\Model;

use Hokodo\BNPL\Api\Data\DeferredPaymentInterface;
use Hokodo\BNPL\Api\Data\DeferredPaymentPayloadInterface;
use Hokodo\BNPL\Api\Data\OrderInterface;
use Hokodo\BNPL\Api\DeferredPaymentProcessorInterface;
use Hokodo\BNPL\Gateway\Service\DifferedPayment\OrderProcessor;
use Hokodo\BNPL\Gateway\Service\DifferedPayment\PaymentProcessor;
use Hokodo\BNPL\Observer\DataAssignObserver;
use Magento\Framework\Exception\LocalizedException;
use Magento\Sales\Model\OrderFactory;
use Psr\Log\LoggerInterface;

class DeferredPaymentProcessor implements DeferredPaymentProcessorInterface
{
    /**
     * @var OrderFactory
     */
    private OrderFactory $orderFactory;

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
     * @param OrderProcessor   $orderProcessor
     * @param PaymentProcessor $paymentProcessor
     * @param LoggerInterface  $logger
     */
    public function __construct(
        OrderFactory $orderFactory,
        OrderProcessor $orderProcessor,
        PaymentProcessor $paymentProcessor,
        LoggerInterface $logger
    ) {
        $this->orderFactory = $orderFactory;
        $this->orderProcessor = $orderProcessor;
        $this->paymentProcessor = $paymentProcessor;
        $this->logger = $logger;
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\DeferredPaymentProcessorInterface::process()
     */
    public function process($created, DeferredPaymentPayloadInterface $data)
    {
        try {
            $result = false;
            if ($data->getOrder() && isset($data->getOrder()[OrderInterface::DEFERRED_PAYMENT])) {
                $result = $this->processOrder($data->getOrder());
            }
            return $result;
        } catch (\Exception $e) {
            $data = [
                'message' => 'Hokodo_BNPL: Webhook error with order - ' . $data->getOrder()[OrderInterface::ID],
                'error' => $e->getMessage(),
            ];
            $this->logger->error(__METHOD__, $data);
        }
    }

    /**
     * A function that make process  order.
     *
     * @param array $hokodoOrder
     *
     * @return bool
     *
     * @throws LocalizedException
     */
    private function processOrder(array $hokodoOrder): bool
    {
        $order = $this->orderFactory->create()->loadByIncrementId($hokodoOrder[OrderInterface::UNIQUE_ID]);
        if ($order->getId()
            && $order->getPayment()->getAdditionalInformation()[DataAssignObserver::HOKODO_ORDER_ID]
            === $hokodoOrder[OrderInterface::ID]) {
            $deferredPayment = $hokodoOrder[OrderInterface::DEFERRED_PAYMENT];
            $payment = $order->getPayment();
            $status = $deferredPayment[DeferredPaymentInterface::STATUS];
            $transactionId = $deferredPayment[DeferredPaymentInterface::NUMBER];
            $this->orderProcessor->process($order, $status);
            $this->paymentProcessor->process($payment, $status, $transactionId, true);
            return true;
        }
        return false;
    }
}
