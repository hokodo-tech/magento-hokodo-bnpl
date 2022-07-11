<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Gateway\Response;

use Hokodo\BNPL\Gateway\DeferredPaymentOrderSubjectReader;
use Hokodo\BNPL\Model\SaveLog as PaymentLogger;
use Magento\Payment\Gateway\Response\HandlerInterface;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\Data\OrderInterfaceFactory;
use Magento\Sales\Model\Order;
use Magento\Sales\Model\Spi\OrderResourceInterface;

class DeferredPaymentFetchStatusHandler implements HandlerInterface
{
    /**
     * @var DeferredPaymentOrderSubjectReader
     */
    private $subjectReader;

    /**
     * @var OrderResourceInterface
     */
    private $orderResource;

    /**
     * @var OrderInterfaceFactory
     */
    private $orderFactory;

    /**
     * @var PaymentLogger
     */
    private $paymentLogger;

    /**
     * @param DeferredPaymentOrderSubjectReader $subjectReader
     * @param OrderResourceInterface            $orderResource
     * @param OrderInterfaceFactory             $orderFactory
     * @param PaymentLogger                     $paymentLogger
     */
    public function __construct(
        DeferredPaymentOrderSubjectReader $subjectReader,
        OrderResourceInterface $orderResource,
        OrderInterfaceFactory $orderFactory,
        PaymentLogger $paymentLogger
    ) {
        $this->subjectReader = $subjectReader;
        $this->orderResource = $orderResource;
        $this->orderFactory = $orderFactory;
        $this->paymentLogger = $paymentLogger;
    }

    /**
     * @inheritDoc
     *
     * @see \Magento\Payment\Gateway\Response\HandlerInterface::handle()
     */
    public function handle(array $handlingSubject, array $response)
    {
        if (isset($response['status'])) {
            $log['response'] = $response;
            $payment = $this->subjectReader->readPayment($handlingSubject);
            $ipnOrder = $this->subjectReader->readOrder($handlingSubject);
            switch ($response['status']) {
                case 'accepted':
                    $payment->setData('transaction_id', $response['number']);
                    $payment->setData('is_transaction_approved', true);
                    $incrementId = $ipnOrder->getOrderIncrementId();
                    $order = $this->getOrder($incrementId);
                    if ($order->getState() === Order::STATE_PAYMENT_REVIEW) {
                        $order->setState(Order::STATE_PROCESSING);
                        $order->save();
                        $log['updated_order_status'] = 'IncrementId: ' .
                            $incrementId . '. Updated order status to: ' . Order::STATE_PROCESSING;
                        $data = [
                            'payment_log_content' => $log,
                            'action_title' => 'DeferredPaymentFetchStatusHandler::handle',
                            'status' => 1,
                        ];
                        $this->paymentLogger->execute($data);
                    }
                    break;
                case 'pending_review':
                case 'customer_action_required':
                    $payment->setData('is_transaction_pending', true);
                    break;
                case 'rejected':
                    $payment->setData('transaction_id', $response['number']);
                    $payment->setData('is_transaction_denied', true);
                    break;
            }
        }
    }

    /**
     * Get order by increment id.
     *
     * @param int $incrementId
     *
     * @return OrderInterface
     */
    private function getOrder($incrementId)
    {
        $order = $this->orderFactory->create();
        $this->orderResource->load($order, $incrementId, OrderInterface::INCREMENT_ID);
        return $order;
    }
}
