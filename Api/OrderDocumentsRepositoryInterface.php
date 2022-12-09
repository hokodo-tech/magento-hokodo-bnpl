<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Hokodo\BNPL\Api;

use Hokodo\BNPL\Api\Data\OrderDocumentInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResults;

interface OrderDocumentsRepositoryInterface
{
    /**
     * Get order documents list.
     *
     * @param SearchCriteriaInterface $searchCriteria
     *
     * @return SearchResults
     */
    public function getList(SearchCriteriaInterface $searchCriteria): SearchResults;

    /**
     * Save document.
     *
     * @param OrderDocumentInterface $orderApiDocument
     *
     * @return void
     */
    public function save(OrderDocumentInterface $orderApiDocument): void;
}
