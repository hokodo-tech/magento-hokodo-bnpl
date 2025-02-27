<?php

/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Hokodo\BNPL\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class HokodoCustomerDev extends AbstractDb
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'hokodo_customer_resource';

    /**
     * Initialize resource model.
     */
    protected function _construct()
    {
        $this->_init('hokodo_customer_dev', 'id');
    }

    /**
     * @inheritDoc
     */
    protected function _getLoadSelect($field, $value, $object)
    {
        $select = parent::_getLoadSelect($field, $value, $object);
        $select->where('env=?', $object->getData('env'));

        return $select;
    }
}
