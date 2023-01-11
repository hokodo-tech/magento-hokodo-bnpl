<?php
/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Hokodo\BNPL\Model\ResourceModel;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class QuoteAddress extends AbstractDb
{
    /**
     * A constructor.
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('quote_address', 'address_id');
    }

    /**
     * A function that sets customer address email.
     *
     * @param int    $quoteId
     * @param string $email
     *
     * @return mixed
     *
     * @throws LocalizedException
     */
    public function setCustomerEmailToQuoteAddressByQuoteId($quoteId, $email)
    {
        $connection = $this->getConnection();
        return $connection->update(
            $this->getMainTable(),
            ['email' => $email],
            ['quote_id = ?' => (int) $quoteId]
        );
    }
}
