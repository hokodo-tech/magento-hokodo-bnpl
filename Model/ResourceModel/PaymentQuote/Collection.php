<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Model\ResourceModel\PaymentQuote;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * Class Hokodo\BNPL\Model\ResourceModel\PaymentQuote\Collection.
 */
class Collection extends AbstractCollection
{
    /**
     * @inheritDoc
     *
     * @see \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection::_construct()
     */
    protected function _construct()
    {
        $this->_init(
            \Hokodo\BNPL\Model\PaymentQuote::class,
            \Hokodo\BNPL\Model\ResourceModel\PaymentQuote::class
        );
    }
}
