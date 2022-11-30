<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Hokodo\BNPL\Model;

use Hokodo\BNPL\Api\Data\OrderDocumentInterface;
use Hokodo\BNPL\Api\OrderDocumentsRepositoryInterface;
use Hokodo\BNPL\Model\ResourceModel\OrderDocument as OrderDocumentResource;
use Hokodo\BNPL\Model\ResourceModel\OrderDocument\Collection;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResults;
use Magento\Framework\Api\SearchResultsFactory;
use Magento\Framework\Exception\AlreadyExistsException;

class OrderDocumentsRepository implements OrderDocumentsRepositoryInterface
{
    /**
     * @var Collection
     */
    private Collection $orderDocumentCollection;

    /**
     * @var CollectionProcessorInterface
     */
    private CollectionProcessorInterface $collectionProcessor;

    /**
     * @var SearchResultsFactory
     */
    private SearchResultsFactory $searchResultsFactory;

    /**
     * @var OrderDocumentResource
     */
    private OrderDocumentResource $orderDocumentResource;

    /**
     * @var OrderDocumentFactory
     */
    private OrderDocumentFactory $orderDocumentFactory;

    /**
     * @param OrderDocumentFactory         $orderDocumentFactory
     * @param OrderDocumentResource        $orderDocumentResource
     * @param Collection                   $orderDocumentCollection
     * @param CollectionProcessorInterface $collectionProcessor
     * @param SearchResultsFactory         $searchResultsFactory
     */
    public function __construct(
        OrderDocumentFactory $orderDocumentFactory,
        OrderDocumentResource $orderDocumentResource,
        Collection $orderDocumentCollection,
        CollectionProcessorInterface $collectionProcessor,
        SearchResultsFactory $searchResultsFactory
    ) {
        $this->orderDocumentCollection = $orderDocumentCollection;
        $this->collectionProcessor = $collectionProcessor;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->orderDocumentResource = $orderDocumentResource;
        $this->orderDocumentFactory = $orderDocumentFactory;
    }

    /**
     * @inheritDoc
     */
    public function getList(SearchCriteriaInterface $searchCriteria): SearchResults
    {
        $collection = $this->orderDocumentCollection->create();
        $this->collectionProcessor->process($searchCriteria, $collection);

        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());

        return $searchResults;
    }

    /**
     * @inheritDoc
     *
     * @throws AlreadyExistsException
     */
    public function save(OrderDocumentInterface $orderApiDocument): void
    {
        $orderDocument = $this->orderDocumentFactory->create()->setData($orderApiDocument->getData());
        $this->orderDocumentResource->save($orderDocument);
    }
}
