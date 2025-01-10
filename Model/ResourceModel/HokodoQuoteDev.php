<?php

/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Hokodo\BNPL\Model\ResourceModel;

use Hokodo\BNPL\Api\Data\HokodoQuoteInterface;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class HokodoQuoteDev extends AbstractDb
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'hokodo_quote_resource_model';

    /**
     * Initialize resource model.
     */
    protected function _construct()
    {
        $this->_init('hokodo_quote_dev', HokodoQuoteInterface::ID);
    }

    /**
     * @inheritDoc
     */
    protected function _getLoadSelect($field, $value, $object)
    {
        $select = parent::_getLoadSelect($field, $value, $object);
        $envField = $this->getConnection()->quoteIdentifier(sprintf('%s.%s', $this->getMainTable(), 'env'));
        $select->where($envField . '=?', $object->getData('env'));

        return $select;
    }
}
