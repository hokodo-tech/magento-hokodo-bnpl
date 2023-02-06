<?php

declare(strict_types=1);
/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Plugin\Payment\Model\Checks;

use Hokodo\BNPL\Gateway\Config\Config;
use Magento\Payment\Model\Checks\TotalMinMax;
use Magento\Payment\Model\MethodInterface;
use Magento\Quote\Model\Quote;

class TotalMinMaxPlugin
{
    public const MIN_ORDER_TOTAL = 'advanced/min_order_total';
    public const MAX_ORDER_TOTAL = 'advanced/max_order_total';

    /**
     * After isApplicable.
     *
     * @param TotalMinMax     $subject
     * @param bool            $result
     * @param MethodInterface $paymentMethod
     * @param Quote           $quote
     *
     * @return bool
     */
    public function afterIsApplicable(
        TotalMinMax $subject,
        bool $result,
        MethodInterface $paymentMethod,
        Quote $quote
    ): bool {
        if ($paymentMethod->getCode() == Config::CODE) {
            $total = $quote->getBaseGrandTotal();
            $minTotal = $paymentMethod->getConfigData(self::MIN_ORDER_TOTAL);
            $maxTotal = $paymentMethod->getConfigData(self::MAX_ORDER_TOTAL);
            if (!empty($minTotal) && $total < $minTotal || !empty($maxTotal) && $total > $maxTotal) {
                return false;
            }
            return true;
        }
        return $result;
    }
}
