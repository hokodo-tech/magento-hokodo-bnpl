<?php

declare(strict_types=1);
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Ui\Component\Listing\Column;

/**
 * Class Link404Status.
 */
class PaymentLogStatus implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * Options getter.
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => 0, 'label' => __('Error')],
            ['value' => 1, 'label' => __('Success')],
        ];
    }
}
