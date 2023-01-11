<?php
/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Gateway\Response;

use Hokodo\BNPL\Gateway\DeferredPaymentOrderSubjectReader;
use Magento\Payment\Gateway\Response\HandlerInterface;
use Magento\Sales\Api\Data\OrderPaymentInterface;

/**
 * Class Hokodo\BNPL\Gateway\Response\DeferredPaymentCaptureHandler.
 */
class DeferredPaymentCaptureHandler implements HandlerInterface
{
    /**
     * @var DeferredPaymentOrderSubjectReader
     */
    private $subjectReader;

    /**
     * @param DeferredPaymentOrderSubjectReader $subjectReader
     */
    public function __construct(DeferredPaymentOrderSubjectReader $subjectReader)
    {
        $this->subjectReader = $subjectReader;
    }

    /**
     * @inheritDoc
     *
     * @see \Magento\Payment\Gateway\Response\HandlerInterface::handle()
     */
    public function handle(array $handlingSubject, array $response)
    {
        if (isset($response['status'])) {
            /**
             * @var OrderPaymentInterface $payment
             */
            $payment = $this->subjectReader->readPayment($handlingSubject);

            $payment->setTransactionId($response['number']);
            $payment->setIsTransactionClosed(false);

            switch ($response['status']) {
                case 'pending_review':
                case 'customer_action_required':
                    /* @var $payment OrderPaymentInterface */
                    $payment->setIsTransactionPending(true);
                    $payment->setIsFraudDetected(false);
                    break;
                case 'rejected':
                    $payment->setIsTransactionPending(true);
                    $payment->setIsFraudDetected(true);
                    break;
                case 'fulfilled':
                    $payment->setIsTransactionPending(false);
                    break;
            }
        }
    }
}
