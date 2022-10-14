<?php

/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Model\Adminhtml\Source;

use Magento\Framework\Data\OptionSourceInterface;

class PaymentMethodLogos implements OptionSourceInterface
{
    public const CREDIT_DEBIT = 'default';
    public const CREDIT_CARDS = 'credit';
    public const DIRECT_DEBIT = 'direct';
    public const NONE_CARDS = 'none';

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
               'value' => self::DIRECT_DEBIT,
               'label' => __(' Direct Debit'),
            ],
            [
               'value' => self::CREDIT_DEBIT,
               'label' => __('Credit Card and Direct Debit'),
            ],
            [
               'value' => self::NONE_CARDS,
               'label' => __('None'),
            ],
        ];
    }
}
