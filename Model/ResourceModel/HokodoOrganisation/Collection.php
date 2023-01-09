<?php
/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Model\ResourceModel\HokodoOrganisation;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

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
            \Hokodo\BNPL\Model\HokodoOrganisation::class,
            \Hokodo\BNPL\Model\ResourceModel\HokodoOrganisation::class
        );
    }
}
