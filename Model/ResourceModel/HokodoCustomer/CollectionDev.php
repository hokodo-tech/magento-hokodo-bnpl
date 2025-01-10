<?php

/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Hokodo\BNPL\Model\ResourceModel\HokodoCustomer;

use Hokodo\BNPL\Model\Data\HokodoCustomer as Model;
use Hokodo\BNPL\Model\ResourceModel\HokodoCustomerDev as ResourceModel;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class CollectionDev extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'hokodo_customer_collection';

    /**
     * Initialize collection model.
     */
    protected function _construct()
    {
        $this->_init(Model::class, ResourceModel::class);
    }

    /**
     * Add env filter for dev/sandbox mode.
     *
     * @param int $envId
     *
     * @return $this
     */
    public function addEnvFilter(int $envId): self
    {
        $envField = $this->getConnection()->quoteIdentifier(sprintf('%s.%s', $this->getMainTable(), 'env'));
        $this->getSelect()->where($envField . '=?', $envId);

        return $this;
    }
}
