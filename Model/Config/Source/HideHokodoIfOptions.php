<?php
/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Hokodo\BNPL\Model\Config\Source;

class HideHokodoIfOptions implements \Magento\Framework\Data\OptionSourceInterface
{
    public const IF_ORDER_ELIGIBLE = 1;
    public const COMPANY_IS_NOT_ATTACHED = 2;

    public const IF_ORDER_ELIGIBLE_CODE = 'order_is_not_eligible';
    public const COMPANY_IS_NOT_ATTACHED_CODE = 'company_is_not_attached';
    public const BOTH_CODE = 'order_is_not_eligible_or_company_is_not_attached';
    public const DONT_HIDE_CODE = 'dont_hide';

    /**
     * @inheritdoc
     */
    public function toOptionArray(): array
    {
        return [
            [
                'value' => self::IF_ORDER_ELIGIBLE,
                'label' => __("Order isn't Eligible"),
            ],
            [
                'value' => self::COMPANY_IS_NOT_ATTACHED,
                'label' => __("Company isn't attached"),
            ],
        ];
    }
}
