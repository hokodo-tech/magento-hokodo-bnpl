<?php

declare(strict_types=1);
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context;

class PaymentLog extends AbstractDb
{
    /**
     * Resource model PaymentLog __contructor.
     *
     * @param Context $context
     */
    public function __construct(// @codingStandardsIgnoreLine
        Context $context
    ) {
        parent::__construct($context);
    }

    /**
     * Resource model PaymentLog _contructor.
     */
    protected function _construct() // @codingStandardsIgnoreLine
    {
        $this->_init('hokodo_payment_logs', 'payment_log_id');
    }
}
