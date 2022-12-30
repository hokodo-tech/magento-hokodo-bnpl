<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Hokodo\BNPL\Model;

use Hokodo\BNPL\Api\Data\DeferredPaymentIpnPayloadInterface;
use Hokodo\BNPL\Api\Data\OrderIpnInterface;
use Hokodo\BNPL\Api\DeferredPaymentIpnProcessorInterface;
use Hokodo\BNPL\Model\Data\OrderIpnFactory;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Webapi\Exception;
use Magento\Sales\Model\Order;
use Magento\Sales\Model\OrderFactory;
use Psr\Log\LoggerInterface;

class DeferredPaymentIpnProcessor implements DeferredPaymentIpnProcessorInterface
{
    /**
     * @var OrderFactory
     */
    private $orderFactory;

    /**
     * @var DataObjectHelper
     */
    private $dataObjectHelper;

    /**
     * @var OrderIpnFactory
     */
    private $orderIpnFactory;

    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * A construct form deferred payment ipl processor.
     *
     * @param OrderFactory     $orderFactory
     * @param OrderIpnFactory  $orderIpnFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param LoggerInterface  $logger
     */
    public function __construct(
        OrderFactory $orderFactory,
        OrderIpnFactory $orderIpnFactory,
        DataObjectHelper $dataObjectHelper,
        LoggerInterface $logger
    ) {
        $this->orderFactory = $orderFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->orderIpnFactory = $orderIpnFactory;
        $this->logger = $logger;
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\DeferredPaymentIpnProcessorInterface::process()
     */
    public function process($created, DeferredPaymentIpnPayloadInterface $data)
    {
        $orderData = $data->getOrder();
        try {
            $result = false;
            $ipnOrder = $this->orderIpnFactory->create();
            if (empty($orderData['payment_offer'])) {
                throw new Exception(
                    __('Payment Offer does not provided.')
                );
            }
            $this->dataObjectHelper->populateWithArray(
                $ipnOrder,
                $orderData,
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
                'order_data' => $orderData,
            ];
            $this->logger->error(__METHOD__, $data);
        }
        return false;
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
    private function processIpnOrder(OrderIpnInterface $ipnOrder)
    {
        $order = $this->orderFactory->create()->loadByAttribute('increment_id', $ipnOrder->getUniqueId());
        if ($order->getId()
            && $order->getPayment()->getAdditionalInformation()['hokodo_order_id'] === $ipnOrder->getId()) {
            $deferredPayment = $ipnOrder->getDeferredPayment();
            $payment = $order->getPayment();
            switch ($deferredPayment->getStatus()) {
                case 'accepted':
                    $payment->setTransactionId($deferredPayment->getNumber());
                    $payment->setIsTransactionApproved(true);
                    $payment->update(false);
                    break;
                case 'pending_review':
                case 'customer_action_required':
                    $payment->setIsTransactionPending(true);
                    break;
                case 'rejected':
                    $payment->setTransactionId($deferredPayment->getNumber());
                    $payment->setIsTransactionDenied(true);
                    $payment->update(false);
                    break;
                case 'fulfilled':
                    if ($order->getState() === Order::STATE_PENDING_PAYMENT) {
                        $order->setState(Order::STATE_PROCESSING);
                    }
                    break;
            }
            $order->save();
            return true;
        }

        return false;
    }
}
