<?php
/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Order extends AbstractDb
{
    /**
     * A constructor.
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('sales_order', 'entity_id');
    }

    /**
     * A function that gets customer email.
     *
     * @param int $quoteId
     *
     * @return mixed
     */
    public function getCustomerEmailFromOrderByQuoteId($quoteId)
    {
        $connection = $this->getConnection();
        $select = $connection->select()->from($this->getMainTable(), 'customer_email')
            ->where('quote_id = :quote_id');
        $bind = [':quote_id' => (string) $quoteId];

        return $connection->fetchOne($select, $bind);
    }
}
