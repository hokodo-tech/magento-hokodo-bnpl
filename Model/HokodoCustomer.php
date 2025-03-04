<?php

/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Hokodo\BNPL\Model;

use Hokodo\BNPL\Model\ResourceModel\HokodoCustomer as ResourceModel;

class HokodoCustomer extends \Magento\Framework\Model\AbstractExtensibleModel
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'hokodo_customer';

    /**
     * Initialize magento model.
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(ResourceModel::class);
    }
}
