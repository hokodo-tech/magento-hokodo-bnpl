<?php
/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Hokodo\BNPL\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

class PaymentMethodBehaviour implements OptionSourceInterface
{
    public const IS_DEFAULT_NO = 0;
    public const IS_DEFAULT_YES = 1;
    public const IF_ORDER_ELIGIBLE = 2;

    /**
     * @inheritdoc
     */
    public function toOptionArray(): array
    {
        return [
            [
                'value' => self::IS_DEFAULT_NO,
                'label' => __('No'),
            ],
            [
                'value' => self::IS_DEFAULT_YES,
                'label' => __('Yes'),
            ],
            [
                'value' => self::IF_ORDER_ELIGIBLE,
                'label' => __('if Order is eligible'),
            ],
        ];
    }
}
