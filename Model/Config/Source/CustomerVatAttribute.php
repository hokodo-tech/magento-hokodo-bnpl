<?php

/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Hokodo\BNPL\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

class CustomerVatAttribute implements OptionSourceInterface
{
    public const DISABLED = 'disabled';
    public const MODEL = 'model_attribute';
    public const CUSTOM = 'custom_attribute';

    /**
     * Get Options Array.
     *
     * @return array[]
     */
    public function toOptionArray(): array
    {
        return [
            [
                'value' => self::DISABLED,
                'label' => __('Disabled'),
            ],
            [
                'value' => self::MODEL,
                'label' => __('Customer Model Attribute'),
            ],
            [
                'value' => self::CUSTOM,
                'label' => __('Customer Custom Attribute'),
            ],
        ];
    }
}
