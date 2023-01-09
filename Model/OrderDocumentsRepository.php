<?php
/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Hokodo\BNPL\Model;

use Hokodo\BNPL\Api\Data\OrderDocumentInterface;
use Hokodo\BNPL\Api\OrderDocumentsRepositoryInterface;
use Hokodo\BNPL\Model\ResourceModel\OrderDocument as OrderDocumentResource;
use Hokodo\BNPL\Model\ResourceModel\OrderDocument\CollectionFactory;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResults;
use Magento\Framework\Api\SearchResultsFactory;
use Magento\Framework\Exception\AlreadyExistsException;

class OrderDocumentsRepository implements OrderDocumentsRepositoryInterface
{
    /**
     * @var CollectionFactory
     */
    private CollectionFactory $orderDocumentCollectionFactory;

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
     * @param CollectionFactory            $orderDocumentCollectionFactory
     * @param CollectionProcessorInterface $collectionProcessor
     * @param SearchResultsFactory         $searchResultsFactory
     */
    public function __construct(
        OrderDocumentFactory $orderDocumentFactory,
        OrderDocumentResource $orderDocumentResource,
        CollectionFactory $orderDocumentCollectionFactory,
        CollectionProcessorInterface $collectionProcessor,
        SearchResultsFactory $searchResultsFactory
    ) {
        $this->orderDocumentFactory = $orderDocumentFactory;
        $this->orderDocumentResource = $orderDocumentResource;
        $this->orderDocumentCollectionFactory = $orderDocumentCollectionFactory;
        $this->collectionProcessor = $collectionProcessor;
        $this->searchResultsFactory = $searchResultsFactory;
    }

    /**
     * @inheritDoc
     */
    public function getList(SearchCriteriaInterface $searchCriteria): SearchResults
    {
        $collection = $this->orderDocumentCollectionFactory->create();
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
