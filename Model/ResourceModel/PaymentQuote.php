<?php
/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Model\ResourceModel;

use Hokodo\BNPL\Api\Data\PaymentQuoteInterface;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Class Hokodo\BNPL\Model\ResourceModel\PaymentQuote.
 */
class PaymentQuote extends AbstractDb
{
    public const TABLE_NAME = 'hokodo_payment_quote';

    /**
     * @inheritDoc
     *
     * @see \Magento\Framework\Model\ResourceModel\AbstractResource::_construct()
     */
    protected function _construct()
    {
        $this->_init(self::TABLE_NAME, PaymentQuoteInterface::PAYMENT_QUOTE_ID);
    }

    /**
     * A function that gets by quote id.
     *
     * @param int $quoteId
     *
     * @return int
     */
    public function getByQuoteId($quoteId)
    {
        $connection = $this->getConnection();

        $select = $connection->select()->from($this->getMainTable(), PaymentQuoteInterface::PAYMENT_QUOTE_ID)
        ->where(PaymentQuoteInterface::QUOTE_ID . ' = :' . PaymentQuoteInterface::QUOTE_ID);

        $bind = [':' . PaymentQuoteInterface::QUOTE_ID => (string) $quoteId];

        return $connection->fetchOne($select, $bind);
    }

    /**
     * A function that gets order by id.
     *
     * @param string $orderId
     *
     * @return int
     */
    public function getByOrderId($orderId)
    {
        $connection = $this->getConnection();

        $select = $connection->select()->from($this->getMainTable(), PaymentQuoteInterface::PAYMENT_QUOTE_ID)
        ->where(PaymentQuoteInterface::ORDER_ID . ' = :' . PaymentQuoteInterface::ORDER_ID);

        $bind = [':' . PaymentQuoteInterface::ORDER_ID => (string) $orderId];

        return $connection->fetchOne($select, $bind);
    }

    /**
     * A function that gets magento quote id by hokodo quote id.
     *
     * @param int $hokodoQuoteId
     *
     * @return string
     *
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getMagentoQuoteIdByHokodoQuoteId($hokodoQuoteId)
    {
        $connection = $this->getConnection();
        $select = $connection->select()->from($this->getMainTable(), 'quote_id')
        ->where('payment_quote_id = :quote_id');

        $bind = [':quote_id' => (string) $hokodoQuoteId];

        return $connection->fetchOne($select, $bind);
    }
}
