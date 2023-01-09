<?php
/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Hokodo\BNPL\Service;

use Magento\Framework\Phrase;

class PaymentTerms
{
    public const PAYMENT_TERMS_RELATIVE_TO_FIRST_CAPTURE = 'first_capture';

    public const PAYMENT_TERMS_RELATIVE_TO_EVERY_CAPTURE = 'every_capture';

    /**
     * Try to get Payment Terms.
     *
     * @param string $name
     * @param string $paymentTermsRelativeTo
     *
     * @return Phrase|null
     */
    public function getPaymentTerms(string $name, string $paymentTermsRelativeTo): ?Phrase
    {
        $name = $this->toLowerCase($name);

        $paymentTermsRelativeTo = $this->toLowerCase($paymentTermsRelativeTo);

        $numberOfDays = $this->extractDaysFromName($name);

        $isEndOfMonth = $this->isEndOfMonth($name);

        $isAfterDelivery = $this->isAfterDelivery($paymentTermsRelativeTo);

        return $this->getPaymentTermsPhrase($numberOfDays, $isEndOfMonth, $isAfterDelivery);
    }

    /**
     * Convert string to lower case and trim it.
     *
     * @param string $string
     *
     * @return string
     */
    private function toLowerCase(string $string): string
    {
        return trim(strtolower($string));
    }

    /**
     * Try to extract number of days from internally used name.
     *
     * @param string $name
     *
     * @return int
     */
    private function extractDaysFromName(string $name): int
    {
        if (strpos($name, 'd') === false) {
            return 0;
        }
        return (int) $name;
    }

    /**
     * If 'eo' found in name it is End Of Month payment.
     *
     * @param string $name
     *
     * @return bool
     */
    private function isEndOfMonth(string $name): bool
    {
        $isEndOfMonth = false;
        if (strpos($name, 'eo') !== false) {
            $isEndOfMonth = true;
        }
        return $isEndOfMonth;
    }

    /**
     * Check is payment terms should be After Delivery.
     *
     * @param string $paymentTermsRelativeTo
     *
     * @return bool
     */
    private function isAfterDelivery(string $paymentTermsRelativeTo): bool
    {
        $isAfterDelivery = false;
        if ($paymentTermsRelativeTo == self::PAYMENT_TERMS_RELATIVE_TO_FIRST_CAPTURE
            || $paymentTermsRelativeTo == self::PAYMENT_TERMS_RELATIVE_TO_EVERY_CAPTURE) {
            $isAfterDelivery = true;
        }
        return $isAfterDelivery;
    }

    /**
     * Get Payment Terms Phrase based on conditions.
     *
     * @param int  $numberOfDays
     * @param bool $isEndOfMonth
     * @param bool $isAfterDelivery
     *
     * @return Phrase|null
     */
    private function getPaymentTermsPhrase(
        int $numberOfDays,
        bool $isEndOfMonth,
        bool $isAfterDelivery
    ): ?Phrase {
        $paymentTermsPhrase = null;

        if ($numberOfDays) {
            $paymentTermsPhrase = __('%1 days', $numberOfDays);
        }

        if ($isEndOfMonth) {
            $paymentTermsPhrase = __('%1 days end of month', $numberOfDays);
        }

        if ($isAfterDelivery) {
            $paymentTermsPhrase = __('%1 days after delivery', $numberOfDays);
        }

        if ($isEndOfMonth && $isAfterDelivery) {
            $paymentTermsPhrase = __('%1 days end of month after delivery', $numberOfDays);
        }

        return $paymentTermsPhrase;
    }
}
