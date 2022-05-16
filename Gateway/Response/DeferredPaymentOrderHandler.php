<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Gateway\Response;

use Hokodo\BNPL\Gateway\DeferredPaymentOrderSubjectReader;
use Magento\Payment\Gateway\Response\HandlerInterface;

/**
 * Class Hokodo\BNPL\Gateway\Response\DeferredPaymentOrderHandler.
 */
class DeferredPaymentOrderHandler implements HandlerInterface
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
             * @var \Magento\Payment\Model\InfoInterface $payment
             */
            $payment = $this->subjectReader->readPayment($handlingSubject);

            $payment->setTransactionId($response['number']);
            $payment->setIsTransactionClosed(false);

            switch ($response['status']) {
                case 'accepted':
                    $payment->setIsTransactionPending(false);
                    $payment->setIsFraudDetected(false);
                    break;
                case 'pending_review':
                case 'customer_action_required':
                    /* @var $payment Payment */
                    $payment->setIsTransactionPending(true);
                    $payment->setIsFraudDetected(false);
                    break;
                case 'rejected':
                    $payment->setIsTransactionPending(false);
                    $payment->setIsFraudDetected(true);
                    break;
            }
        }
    }
}
