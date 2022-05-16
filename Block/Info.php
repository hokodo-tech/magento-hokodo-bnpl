<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Block;

use Magento\Framework\Phrase;
use Magento\Payment\Block\ConfigurableInfo;

/**
 * Class Hokodo\BNPL\Block\Info.
 */
class Info extends ConfigurableInfo
{
    /**
     * Returns label.
     *
     * @param string $field
     *
     * @return Phrase
     */
    protected function getLabel($field)
    {
        return __($field);
    }
}
