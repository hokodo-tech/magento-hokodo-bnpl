<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Gateway\Response;

use Hokodo\BNPL\Api\Data\DeferredPaymentInterface;
use Hokodo\BNPL\Api\OrderAdapterReaderInterface;
use Hokodo\BNPL\Gateway\DeferredPaymentOrderSubjectReader;
use Magento\Payment\Gateway\Data\OrderAdapterInterface;
use Magento\Payment\Gateway\Response\HandlerInterface;
use Magento\Sales\Api\Data\OrderPaymentInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Model\Order;

class DeferredPaymentFetchStatusHandler implements HandlerInterface, OrderAdapterReaderInterface
{
    /**
     * @var DeferredPaymentOrderSubjectReader
     */
    private DeferredPaymentOrderSubjectReader $subjectReader;

    /**
     * @var OrderRepositoryInterface
     */
    private OrderRepositoryInterface $orderRepository;

    /**
     * @param DeferredPaymentOrderSubjectReader $subjectReader
     * @param OrderRepositoryInterface          $orderRepository
     */
    public function __construct(
        DeferredPaymentOrderSubjectReader $subjectReader,
        OrderRepositoryInterface $orderRepository
    ) {
        $this->subjectReader = $subjectReader;
        $this->orderRepository = $orderRepository;
    }

    /**
     * @inheritDoc
     *
     * @see \Magento\Payment\Gateway\Response\HandlerInterface::handle()
     */
    public function handle(array $handlingSubject, array $response)
    {
        if (isset($response[DeferredPaymentInterface::STATUS])) {
            /** @var OrderPaymentInterface $payment */
            $payment = $this->subjectReader->readPayment($handlingSubject);
            $orderAdapter = $this->getOrderAdapter($handlingSubject);
            switch ($response[DeferredPaymentInterface::STATUS]) {
                case DeferredPaymentInterface::STATUS_ACCEPTED:
                    $payment
                        ->setTransactionId($response[DeferredPaymentInterface::NUMBER])
                        ->setData('is_transaction_approved', true);
                    $order = $this->orderRepository->get($orderAdapter->getId());
                    if ($order->getState() === Order::STATE_PAYMENT_REVIEW) {
                        $order->setState(Order::STATE_PROCESSING);
                        $this->orderRepository->save($order);
                    }
                    break;
                case DeferredPaymentInterface::STATUS_PENDING:
                case DeferredPaymentInterface::STATUS_ACTION_REQUIRED:
                    $payment->setIsTransactionPending(true);
                    break;
                case DeferredPaymentInterface::STATUS_REJECTED:
                    $payment
                        ->setTransactionId($response[DeferredPaymentInterface::NUMBER])
                        ->setData('is_transaction_denied', true);
                    break;
            }
        }
    }

    /**
     * Get order adapter object.
     *
     * @param array $subject
     *
     * @return OrderAdapterInterface
     */
    public function getOrderAdapter(array $subject): OrderAdapterInterface
    {
        return $this->subjectReader->readOrder($subject);
    }
}
