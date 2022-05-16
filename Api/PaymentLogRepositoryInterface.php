<?php

declare(strict_types=1);
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResults;

/**
 * Interface PaymentLogRepositoryInterface.
 *
 * @api
 */
interface PaymentLogRepositoryInterface
{
    /**
     * Create payment log.
     *
     * @param \Hokodo\BNPL\Api\Data\PaymentLogInterface $paymentLog
     *
     * @return \Hokodo\BNPL\Api\Data\PaymentLogInterface
     */
    public function save(
        Data\PaymentLogInterface $paymentLog
    ): Data\PaymentLogInterface;

    /**
     * Retrieve payment log by Id.
     *
     * @param int $paymentLogId
     *
     * @return \Hokodo\BNPL\Api\Data\PaymentLogInterface
     */
    public function getById(
        int $paymentLogId
    ): Data\PaymentLogInterface;

    /**
     * Get filtered payment log list.
     *
     * @param SearchCriteriaInterface $searchCriteria
     *
     * @return \Hokodo\BNPL\Api\Data\PaymentLogSearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria): SearchResults;

    /**
     * Remove payment log.
     *
     * @param \Hokodo\BNPL\Api\Data\PaymentLogInterface $paymentLog
     *
     * @return \Hokodo\BNPL\Api\Data\PaymentLogInterface
     */
    public function delete(Data\PaymentLogInterface $paymentLog): bool;

    /**
     * Remove payment log by id.
     *
     * @param int $paymentLogId
     *
     * @return \Hokodo\BNPL\Api\Data\PaymentLogInterface
     */
    public function deleteById(int $paymentLogId): bool;
}
