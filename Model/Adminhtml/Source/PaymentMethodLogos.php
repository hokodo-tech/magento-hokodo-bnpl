<?php

/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Model\Adminhtml\Source;

use Magento\Framework\Data\OptionSourceInterface;

class PaymentMethodLogos implements OptionSourceInterface
{
    public const CREDIT_CARDS = true;
    public const NONE_CARDS = false;
    public const LOGOS_CLASS = ['visa', 'mastercard', 'amex'];

    /**
     * @inheritdoc
     */
    public function toOptionArray()
    {
        return [
            [
               'value' => self::CREDIT_CARDS,
               'label' => __('Credit Card'),
            ],
            [
               'value' => self::NONE_CARDS,
               'label' => __('None'),
            ],
        ];
    }
}
