<?php
/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Gateway\Response;

use Hokodo\BNPL\Api\Data\DeferredPaymentInterface;
use Hokodo\BNPL\Gateway\DeferredPaymentOrderSubjectReader;
use Hokodo\BNPL\Gateway\Service\DifferedPayment\OrderProcessor;
use Hokodo\BNPL\Gateway\Service\DifferedPayment\PaymentProcessor;
use Magento\Payment\Gateway\Response\HandlerInterface;
use Magento\Sales\Api\Data\OrderPaymentInterface;
use Psr\Log\LoggerInterface;

class DeferredPaymentOrderHandler implements HandlerInterface
{
    /**
     * @var DeferredPaymentOrderSubjectReader
     */
    private DeferredPaymentOrderSubjectReader $subjectReader;

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
     * @param DeferredPaymentOrderSubjectReader $subjectReader
     * @param OrderProcessor                    $orderProcessor
     * @param PaymentProcessor                  $paymentProcessor
     * @param LoggerInterface                   $logger
     */
    public function __construct(
        DeferredPaymentOrderSubjectReader $subjectReader,
        OrderProcessor $orderProcessor,
        PaymentProcessor $paymentProcessor,
        LoggerInterface $logger
    ) {
        $this->subjectReader = $subjectReader;
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
            /** @var OrderPaymentInterface $payment */
            $payment = $this->subjectReader->readPayment($handlingSubject);
            $status = $response[DeferredPaymentInterface::STATUS];

            $payment->setTransactionId($response[DeferredPaymentInterface::NUMBER]);
            $payment->setIsTransactionClosed(false);

            try {
                $this->paymentProcessor->process($payment, $status);
            } catch (\Exception $e) {
                $data = [
                    'message' => 'Differed Payment Order Handler Error. Transaction: ' . $payment->getTransactionId(),
                    'response' => $response,
                    'error' => $e->getMessage(),
                ];
                $this->logger->error(__METHOD__, $data);
            }
        }
    }
}
