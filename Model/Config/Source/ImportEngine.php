<?php

/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Hokodo\BNPL\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

class ImportEngine implements OptionSourceInterface
{
    public const QUEUE = 'queue';
    public const DIRECT = 'direct';

    /**
     * Get Options Array.
     *
     * @return array[]
     */
    public function toOptionArray(): array
    {
        return [
            [
                'value' => self::QUEUE,
                'label' => __('Queue'),
            ],
            [
                'value' => self::DIRECT,
                'label' => __('Direct Import'),
            ],
        ];
    }
}
