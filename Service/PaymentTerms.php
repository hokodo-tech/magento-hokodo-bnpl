<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Hokodo\BNPL\Service;

class PaymentTerms
{
    /**
     * Try to get Payment Terms.
     *
     * @param string $name
     * @param string $paymentTermsRelativeTo
     *
     * @return \Magento\Framework\Phrase|null
     */
    public function getPaymentTerms(string $name, string $paymentTermsRelativeTo): ?\Magento\Framework\Phrase
    {
        $afterDelivery = false;
        $endOfMonth = false;
        $name = trim(strtolower($name));
        $paymentTermsRelativeTo = trim(strtolower($paymentTermsRelativeTo));

        if (strpos($name,'d') === false) {
            return null;
        }
        $numberOfDays = (int) $name;

        if ($paymentTermsRelativeTo == 'first_capture' || $paymentTermsRelativeTo == 'every_capture') {
            $afterDelivery = true;
        }

        if (strpos($name, 'eo') !== false) {
            $endOfMonth = true;
        }

        if ($endOfMonth && $afterDelivery) {
            return __('%1 days end of month after delivery', $numberOfDays);
        }

        if ($endOfMonth) {
            return __('%1 days end of month', $numberOfDays);
        }

        if ($afterDelivery) {
            return __('%1 days after delivery', $numberOfDays);
        }

        return __('%1 days', $numberOfDays);
    }
}
