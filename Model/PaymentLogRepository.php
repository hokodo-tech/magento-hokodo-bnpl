<?php

declare(strict_types=1);
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Model;

use Hokodo\BNPL\Api\Data\PaymentLogInterface;
use Hokodo\BNPL\Api\Data\PaymentLogSearchResultsInterfaceFactory;
use Hokodo\BNPL\Api\PaymentLogRepositoryInterface;
use Hokodo\BNPL\Model\ResourceModel\PaymentLog as ResourcePaymentLog;
use Hokodo\BNPL\Model\ResourceModel\PaymentLog\CollectionFactory as PaymentLogCollection;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResults;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class PaymentLogRepository implements PaymentLogRepositoryInterface
{
    /**
     * ResourcePaymentLog variable.
     *
     * @var ResourcePaymentLog
     */
    protected $resource;

    /**
     * PaymentLogFactory variable.
     *
     * @var PaymentLogFactory
     */
    protected $paymentLogFactory;

    /**
     * PaymentLogCollection variable.
     *
     * @var PaymentLogCollection
     */
    protected $paymentLogCollection;

    /**
     * PaymentLogSearchResultsInterfaceFactory variable.
     *
     * @var PaymentLogSearchResultsInterfaceFactory
     */
    protected $searchResultsFactory;

    /**
     * CollectionProcessorInterface variable.
     *
     * @var CollectionProcessorInterface
     */
    protected $collectionProcessor;

    /**
     * PatientRepository constructor.
     *
     * @param ResourcePaymentLog                      $resource
     * @param PaymentLogFactory                       $paymentLogFactory
     * @param PaymentLogSearchResultsInterfaceFactory $searchResultsFactory
     * @param PaymentLogCollection                    $paymentLogCollection
     * @param CollectionProcessorInterface            $collectionProcessor
     */
    public function __construct(
        ResourcePaymentLog $resource,
        PaymentLogFactory $paymentLogFactory,
        PaymentLogSearchResultsInterfaceFactory $searchResultsFactory,
        PaymentLogCollection $paymentLogCollection,
        CollectionProcessorInterface $collectionProcessor
    ) {
        $this->resource = $resource;
        $this->paymentLogFactory = $paymentLogFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->paymentLogCollection = $paymentLogCollection;
        $this->collectionProcessor = $collectionProcessor;
    }

    /**
     * Save PaymentLog.
     *
     * @param \Hokodo\BNPL\Api\Data\PaymentLogInterface $paymentLog
     *
     * @return \Hokodo\BNPL\Api\Data\PaymentLogInterface
     *
     * @throws CouldNotSaveException
     */
    public function save(PaymentLogInterface $paymentLog): PaymentLogInterface
    {
        try {
            $this->resource->save($paymentLog);
        } catch (\Exception $ex) {
            throw new CouldNotSaveException(
                __(
                    'Could not save the PaymentLog: %1',
                    $ex->getMessage()
                )
            );
        }

        return $paymentLog;
    }

    /**
     * Load PaymentLog data by given PaymentLog Identity.
     *
     * @param int $paymentLogId
     *
     * @return \Hokodo\BNPL\Api\Data\PaymentLogInterface
     *
     * @throws NoSuchEntityException
     */
    public function getById(int $paymentLogId): PaymentLogInterface
    {
        /** @var PaymentLog $paymentLog */
        $paymentLog = $this->paymentLogFactory->create();
        $this->resource->load($paymentLog, $paymentLogId);

        if (!$paymentLog->getId()) {
            throw new NoSuchEntityException(__('PaymentLog with id %1 does not exist.', $paymentLogId));
        }

        return $paymentLog;
    }

    /**
     * Load PaymentLog data collection by given search criteria.
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     *
     * @param SearchCriteriaInterface $searchCriteria
     *
     * @return \Hokodo\BNPL\Api\Data\PaymentLogSearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria): SearchResults
    {
        $collection = $this->paymentLogCollection->create();
        $this->collectionProcessor->process($searchCriteria, $collection);

        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());

        return $searchResults;
    }

    /**
     * Delete PaymentLog.
     *
     * @param \Hokodo\BNPL\Api\Data\PaymentLogInterface $paymentLog
     *
     * @return bool
     *
     * @throws CouldNotDeleteException
     */
    public function delete(PaymentLogInterface $paymentLog): bool
    {
        try {
            $this->resource->delete($paymentLog);
        } catch (\Exception $ex) {
            throw new CouldNotDeleteException(
                __(
                    'Could not delete the PaymentLog: %1',
                    $ex->getMessage()
                )
            );
        }

        return true;
    }

    /**
     * Delete PaymentLog by entity id.
     *
     * @param int $paymentLogId
     *
     * @return bool
     *
     * @throws CouldNotDeleteException
     * @throws NoSuchEntityException
     */
    public function deleteById(int $paymentLogId): bool
    {
        return $this->delete($this->getById($paymentLogId));
    }
}
