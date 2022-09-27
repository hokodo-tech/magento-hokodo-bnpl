<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Hokodo\BNPL\Api;

use Hokodo\BNPL\Api\Data\HokodoQuoteInterface;

interface HokodoQuoteRepositoryInterface
{
    /**
     * Save entity.
     *
     * @param HokodoQuoteInterface $hokodoQuote
     *
     * @return HokodoQuoteInterface
     */
    public function save(HokodoQuoteInterface $hokodoQuote): HokodoQuoteInterface;

    /**
     * Delete entity.
     *
     * @param HokodoQuoteInterface $hokodoQuote
     *
     * @return bool
     */
    public function delete(HokodoQuoteInterface $hokodoQuote): bool;

    /**
     * Retrieve Hokodo quote by magento quote id.
     *
     * @param int $quoteId
     *
     * @return HokodoQuoteInterface
     */
    public function getByQuoteId(int $quoteId): HokodoQuoteInterface;

    /**
     * Delete Hokodo quote by magento quote id.
     *
     * @param int $quoteId
     *
     * @return bool
     */
    public function deleteByQuoteId(int $quoteId): bool;
}
