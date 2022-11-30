<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Hokodo\BNPL\Model;

use Hokodo\BNPL\Model\ResourceModel\OrderDocument as ResourceModel;
use Magento\Framework\Model\AbstractModel;

class OrderDocument extends AbstractModel
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'hokodo_order_documents_model';

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
