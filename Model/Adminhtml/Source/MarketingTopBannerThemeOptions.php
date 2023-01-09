<?php
/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Hokodo\BNPL\Model\Adminhtml\Source;

use Magento\Framework\Data\OptionSourceInterface;

class MarketingTopBannerThemeOptions implements OptionSourceInterface
{
    public const DEFAULT = 'default';
    public const DARK = 'dark';
    public const LIGHT = 'light';

    /**
     * @inheritDoc
     */
    public function toOptionArray()
    {
        return [
            [
                'value' => self::DEFAULT,
                'label' => __('Default'),
            ],
            [
                'value' => self::DARK,
                'label' => __('Dark'),
            ],
            [
                'value' => self::LIGHT,
                'label' => __('Light'),
            ],
        ];
    }
}
