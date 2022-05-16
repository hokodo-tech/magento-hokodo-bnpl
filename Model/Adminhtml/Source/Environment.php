<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Model\Adminhtml\Source;

use Hokodo\BNPL\Gateway\Config\Config;
use Magento\Framework\Data\OptionSourceInterface;

/**
 * Class Hokodo\BNPL\Model\Adminhtml\Source\Environment.
 */
class Environment implements OptionSourceInterface
{
    /**
     * @inheritdoc
     */
    public function toOptionArray()
    {
        return [
            [
                'value' => Config::ENV_SANDBOX,
                'label' => __('Sandbox'),
            ],
            [
                'value' => Config::ENV_PRODUCTION,
                'label' => __('Production'),
            ],
        ];
    }
}
