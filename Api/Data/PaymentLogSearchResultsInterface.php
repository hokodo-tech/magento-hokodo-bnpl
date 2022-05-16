<?php

declare(strict_types=1);
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

/**
 * Interface PaymentLogSearchResultsInterface.
 *
 * @api
 */
interface PaymentLogSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get payment log list.
     *
     * @return \Hokodo\BNPL\Api\Data\PaymentLogInterface[]
     */
    public function getItems(): array;

    /**
     * Set payment log list.
     *
     * @param \Hokodo\BNPL\Api\Data\PaymentLogInterface[] $items
     *
     * @return \Hokodo\BNPL\Api\Data\PaymentLogSearchResultsInterface
     */
    public function setItems(array $items);
}
