<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Hokodo\BNPL\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class HokodoDataWebApi extends AbstractDb
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'hokodo_data_webapi_resource_model';

    /**
     * Initialize resource model.
     */
    protected function _construct()
    {
        $this->_init('hokodo_data_webapi', 'id');
        $this->_useIsObjectNew = true;
    }
}
