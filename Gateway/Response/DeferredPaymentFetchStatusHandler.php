<?php
/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Gateway\Response;

use Hokodo\BNPL\Api\Data\DeferredPaymentInterface;
use Hokodo\BNPL\Gateway\Service\DifferedPayment\OrderProcessor;
use Hokodo\BNPL\Gateway\Service\DifferedPayment\PaymentProcessor;
use Magento\Payment\Gateway\Data\PaymentDataObject;
use Magento\Payment\Gateway\Response\HandlerInterface;
use Magento\Sales\Api\Data\OrderPaymentInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Psr\Log\LoggerInterface;

class DeferredPaymentFetchStatusHandler implements HandlerInterface
{
    /**
     * @var OrderRepositoryInterface
     */
    private OrderRepositoryInterface $orderRepository;

    /**
     * @var OrderProcessor
     */
    private OrderProcessor $orderProcessor;

    /**
     * @var PaymentProcessor
     */
    private PaymentProcessor $paymentProcessor;

    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * @param OrderRepositoryInterface $orderRepository
     * @param OrderProcessor           $orderProcessor
     * @param PaymentProcessor         $paymentProcessor
     * @param LoggerInterface          $logger
     */
    public function __construct(
        OrderRepositoryInterface $orderRepository,
        OrderProcessor $orderProcessor,
        PaymentProcessor $paymentProcessor,
        LoggerInterface $logger
    ) {
        $this->orderRepository = $orderRepository;
        $this->orderProcessor = $orderProcessor;
        $this->paymentProcessor = $paymentProcessor;
        $this->logger = $logger;
    }

    /**
     * @inheritDoc
     *
     * @see \Magento\Payment\Gateway\Response\HandlerInterface::handle()
     */
    public function handle(array $handlingSubject, array $response)
    {
        if (isset($response[DeferredPaymentInterface::STATUS])) {
            /** @var PaymentDataObject $paymentData */
            $paymentData = $handlingSubject['payment'];
            /** @var OrderPaymentInterface $payment */
            $payment = $paymentData->getPayment();
            $orderAdapter = $paymentData->getOrder();
            $status = $response[DeferredPaymentInterface::STATUS];
            $transactionId = $response[DeferredPaymentInterface::NUMBER];
            $order = $this->orderRepository->get($orderAdapter->getId());

            try {
                $this->orderProcessor->process($order, $status);
                $this->paymentProcessor->process($payment, $status, $transactionId, true);
            } catch (\Exception $e) {
                $data = [
                    'message' => 'Differed Payment Fetch Status Error for order: ' . $order->getIncrementId(),
                    'response' => $response,
                    'error' => $e->getMessage(),
                ];
                $this->logger->error(__METHOD__, $data);
            }
        }
    }
}
