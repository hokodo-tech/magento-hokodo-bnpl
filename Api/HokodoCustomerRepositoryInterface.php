<?php

/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Hokodo\BNPL\Api;

use Hokodo\BNPL\Api\Data\HokodoCustomerInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResults;

interface HokodoCustomerRepositoryInterface extends HokodoEntityRepositoryInterface
{
    /**
     * Save entity.
     *
     * @param HokodoCustomerInterface $hokodoApiCustomer
     *
     * @return HokodoCustomerInterface
     */
    public function save(HokodoCustomerInterface $hokodoApiCustomer): HokodoCustomerInterface;

    /**
     * Delete entity.
     *
     * @param HokodoCustomerInterface $hokodoCustomer
     *
     * @return bool
     */
    public function delete(HokodoCustomerInterface $hokodoCustomer): bool;

    /**
     * Get List.
     *
     * @param SearchCriteriaInterface $searchCriteria
     *
     * @return SearchResults
     */
    public function getList(SearchCriteriaInterface $searchCriteria): SearchResults;

    /**
     * Get Hokodo Customer by magneto customer id.
     *
     * @param int $customerId
     *
     * @return HokodoCustomerInterface
     */
    public function getByCustomerId(int $customerId): HokodoCustomerInterface;
}
