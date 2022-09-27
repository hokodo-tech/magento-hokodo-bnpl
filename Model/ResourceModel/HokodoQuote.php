<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Hokodo\BNPL\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class HokodoQuote extends AbstractDb
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
        $this->_init('hokodo_quote', 'id');
        $this->_useIsObjectNew = true;
    }
}
