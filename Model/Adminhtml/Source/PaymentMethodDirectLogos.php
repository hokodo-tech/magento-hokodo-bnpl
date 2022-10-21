<?php

/**
 * Copyright © 2018-2022 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Model\Adminhtml\Source;

use Magento\Framework\Data\OptionSourceInterface;

class PaymentMethodDirectLogos implements OptionSourceInterface
{
    public const DIRECT_DEBIT_UK = 'direct_uk';
    public const DIRECT_DEBIT_EU = 'direct_eu';
    public const NONE_CARDS = false;

    /**
     * @inheritdoc
     */
    public function toOptionArray()
    {
        return [
            [
               'value' => self::DIRECT_DEBIT_UK,
               'label' => __('Direct Debit UK'),
            ],
            [
               'value' => self::DIRECT_DEBIT_EU,
               'label' => __('Direct Debit EU'),
            ],
            [
               'value' => self::NONE_CARDS,
               'label' => __('None'),
            ],
        ];
    }
}
