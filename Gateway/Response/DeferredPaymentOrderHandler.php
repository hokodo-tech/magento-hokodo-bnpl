<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Gateway\Response;

use Hokodo\BNPL\Api\Data\DeferredPaymentInterface;
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
        if (isset($response[DeferredPaymentInterface::STATUS])) {
            /**
             * @var \Magento\Payment\Model\InfoInterface $payment
             */
            $payment = $this->subjectReader->readPayment($handlingSubject);

            $payment->setTransactionId($response[DeferredPaymentInterface::NUMBER]);
            $payment->setIsTransactionClosed(false);

            switch ($response[DeferredPaymentInterface::STATUS]) {
                case DeferredPaymentInterface::STATUS_ACCEPTED:
                    $payment->setIsTransactionPending(false);
                    $payment->setIsFraudDetected(false);
                    $payment->setIsTransactionApproved(true);
                    break;
                case DeferredPaymentInterface::STATUS_PENDING:
                case DeferredPaymentInterface::STATUS_ACTION_REQUIRED:
                    /* @var $payment Payment */
                    $payment->setIsTransactionPending(true);
                    $payment->setIsFraudDetected(false);
                    break;
                case DeferredPaymentInterface::STATUS_REJECTED:
                    $payment->setIsTransactionPending(false);
                    $payment->setIsFraudDetected(true);
                    break;
            }
        }
    }
}
