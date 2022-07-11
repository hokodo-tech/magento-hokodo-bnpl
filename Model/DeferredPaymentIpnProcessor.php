<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Hokodo\BNPL\Model;

use Exception;
use Hokodo\BNPL\Api\Data\DeferredPaymentIpnPayloadInterface;
use Hokodo\BNPL\Api\Data\OrderIpnInterface;
use Hokodo\BNPL\Api\DeferredPaymentIpnProcessorInterface;
use Hokodo\BNPL\Model\Data\OrderIpnFactory;
use Hokodo\BNPL\Model\SaveLog as PaymentLogger;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Exception\LocalizedException;
use Magento\Sales\Model\Order;
use Magento\Sales\Model\OrderFactory;

class DeferredPaymentIpnProcessor implements DeferredPaymentIpnProcessorInterface
{
    /**
     * @var OrderFactory
     */
    private $orderFactory;

    /**
     * @var PaymentLogger
     */
    private $paymentLogger;

    /**
     * @var array
     */
    private $debugData = [];

    /**
     * @var DataObjectHelper
     */
    private $dataObjectHelper;

    /**
     * @var OrderIpnFactory
     */
    private $orderIpnFactory;

    /**
     * A construct form deferred payment ipl processor.
     *
     * @param OrderFactory     $orderFactory
     * @param OrderIpnFactory  $orderIpnFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param SaveLog          $paymentLogger
     */
    public function __construct(
        OrderFactory $orderFactory,
        OrderIpnFactory $orderIpnFactory,
        DataObjectHelper $dataObjectHelper,
        PaymentLogger $paymentLogger
    ) {
        $this->orderFactory = $orderFactory;
        $this->paymentLogger = $paymentLogger;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->orderIpnFactory = $orderIpnFactory;
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\DeferredPaymentIpnProcessorInterface::process()
     */
    public function process($created, DeferredPaymentIpnPayloadInterface $data)
    {
        $result = false;
        $ipnOrder = $this->orderIpnFactory->create();
        $this->dataObjectHelper->populateWithArray(
            $ipnOrder,
            $data->getOrder(),
            \Hokodo\BNPL\Api\Data\OrderIpnInterface::class
        );
        $this->addDebugData('ipn', $data);
        if ($ipnOrder && $ipnOrder->getDeferredPayment()) {
            $result = $this->processIpnOrder($ipnOrder);
        }
        $this->debug();
        return $result;
    }

    /**
     * A function that make process ipn order.
     *
     * @param OrderIpnInterface $ipnOrder
     *
     * @throws LocalizedException
     * @throws Exception
     */
    private function processIpnOrder(OrderIpnInterface $ipnOrder)
    {
        $this->addDebugData('ipn-webhook', json_encode($ipnOrder->getDeferredPayment(), JSON_THROW_ON_ERROR));
        $order = $this->orderFactory->create()->loadByAttribute('order_api_id', $ipnOrder->getId());
        if ($order->getId()) {
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
                    $this->addDebugData('fulfilled_payment', $deferredPayment->getNumber());
                    break;
            }
            $order->save();
            return true;
        }

        return false;
    }

    /**
     * A function that adds debug data.
     *
     * @param string $key
     * @param string $value
     *
     * @return $this
     */
    private function addDebugData($key, $value)
    {
        $this->debugData[$key] = $value;
        return $this;
    }

    /**
     * A function that debugs.
     *
     * @return DeferredPaymentIpnProcessor
     *
     * @throws LocalizedException
     */
    private function debug()
    {
        $data = [
            'payment_log_content' => $this->debugData,
            'action_title' => 'DeferredPaymentIpnProcessor',
            'status' => 1,
        ];
        $this->paymentLogger->execute($data);
        return $this;
    }
}
