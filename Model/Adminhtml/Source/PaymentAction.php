<?php
/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Model\Adminhtml\Source;

use Magento\Framework\Data\OptionSourceInterface;
use Magento\Payment\Model\Method\Adapter;

class PaymentAction implements OptionSourceInterface
{
    /**
     * @inheritdoc
     */
    public function toOptionArray()
    {
        return [
            [
                'value' => Adapter::ACTION_AUTHORIZE,
                'label' => __('Authorise'),
            ],
            [
                'value' => Adapter::ACTION_AUTHORIZE_CAPTURE,
                'label' => __('Authorise Capture'),
            ],
            [
                'value' => Adapter::ACTION_ORDER,
                'label' => __('Order'),
            ],
        ];
    }
}
