<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Hokodo\BNPL\Model\ResourceModel\PaymentLog;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'payment_log_id';
    /**
     * @var string
     */
    protected $_eventPrefix = 'hokodo_bnpl_paymentlog_collection';
    /**
     * @var string
     */
    protected $_eventObject = 'PaymentLog_collection';

    /**
     * Define resource model.
     *
     * @return void
     */
    protected function _construct() //@codingStandardsIgnoreLine
    {
        $this->_init(
            \Hokodo\BNPL\Model\PaymentLog::class,
            \Hokodo\BNPL\Model\ResourceModel\PaymentLog::class
        );
    }
}
