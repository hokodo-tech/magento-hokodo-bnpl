<?php
/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Hokodo\BNPL\Gateway\Service\DifferedPayment;

use Hokodo\BNPL\Api\Data\DeferredPaymentInterface;
use Magento\Sales\Api\Data\OrderPaymentInterface;

class PaymentProcessor
{
    /**
     * Process Payment.
     *
     * @param OrderPaymentInterface $payment
     * @param string                $status
     * @param string|null           $transactionId
     * @param bool                  $needOfflineUpdate
     *
     * @return void
     *
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function process(
        OrderPaymentInterface $payment,
        string $status,
        string $transactionId = null,
        bool $needOfflineUpdate = false
    ): void {
        switch ($status) {
            case DeferredPaymentInterface::STATUS_ACCEPTED:
                if ($transactionId) {
                    $payment->setTransactionId($transactionId);
                }
                $payment->setIsTransactionApproved(true);
                $payment->setIsTransactionPending(false);
                $payment->setIsFraudDetected(false);
                if ($needOfflineUpdate) {
                    $payment->update(false);
                }
                break;
            case DeferredPaymentInterface::STATUS_PENDING:
            case DeferredPaymentInterface::STATUS_PENDING_PAYMENT:
            case DeferredPaymentInterface::STATUS_ACTION_REQUIRED:
                $payment->setIsTransactionPending(true);
                $payment->setIsFraudDetected(false);
                break;
            case DeferredPaymentInterface::STATUS_REJECTED:
                if ($transactionId) {
                    $payment->setTransactionId($transactionId);
                }
                $payment->setIsTransactionDenied(true);
                $payment->setIsTransactionPending(false);
                $payment->setIsFraudDetected(true);
                if ($needOfflineUpdate) {
                    $payment->update(false);
                }
                break;
            case DeferredPaymentInterface::STATUS_FULFILLED:
                break;
        }
    }
}
