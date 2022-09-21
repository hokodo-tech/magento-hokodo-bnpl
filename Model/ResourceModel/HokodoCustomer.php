<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Hokodo\BNPL\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class HokodoCustomer extends AbstractDb
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
        $this->_init('hokodo_data_webapi', 'id');
    }
}
