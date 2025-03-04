<?php

/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Hokodo\BNPL\Model;

use Hokodo\BNPL\Api\Data\HokodoCustomerInterface;
use Hokodo\BNPL\Api\Data\HokodoCustomerInterfaceFactory;
use Hokodo\BNPL\Api\Data\HokodoEntityInterface;
use Hokodo\BNPL\Api\HokodoCustomerRepositoryInterface;
use Hokodo\BNPL\Gateway\Config\Config;
use Hokodo\BNPL\Model\Config\Source\Env;
use Hokodo\BNPL\Model\ResourceModel\HokodoCustomer as Resource;
use Hokodo\BNPL\Model\ResourceModel\HokodoCustomer\CollectionDevFactory as HokodoCustomerCollectionDevFactory;
use Hokodo\BNPL\Model\ResourceModel\HokodoCustomer\CollectionFactory as HokodoCustomerCollectionFactory;
use Hokodo\BNPL\Model\ResourceModel\HokodoCustomerDev as ResourceDev;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResults;
use Magento\Framework\Api\SearchResultsFactory;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Serialize\SerializerInterface;

class HokodoCustomerRepository implements HokodoCustomerRepositoryInterface
{
    /**
     * @var resource
     */
    private $resource;

    /**
     * @var HokodoCustomerInterfaceFactory
     */
    private $hokodoCustomerFactory;

    /**
     * @var SerializerInterface
     */
    private SerializerInterface $serializer;

    /**
     * @var DataObjectHelper
     */
    private DataObjectHelper $dataObjectHelper;

    /**
     * @var HokodoCustomerFactory
     */
    private HokodoCustomerFactory $hokodoCustomerModelFactory;

    /**
     * @var HokodoCustomerCollectionFactory
     */
    private HokodoCustomerCollectionFactory $hokodoCustomerCollectionFactory;

    /**
     * @var CollectionProcessorInterface
     */
    private CollectionProcessorInterface $collectionProcessor;

    /**
     * @var SearchResultsFactory
     */
    private SearchResultsFactory $searchResultsFactory;

    /**
     * @var ResourceDev
     */
    private ResourceDev $resourceDev;

    /**
     * @var Config
     */
    private Config $config;

    /**
     * @var Env
     */
    private Env $envSource;

    /**
     * @var HokodoCustomerCollectionDevFactory
     */
    private HokodoCustomerCollectionDevFactory $hokodoCustomerCollectionDevFactory;

    /**
     * @var JoinProcessorInterface
     */
    private JoinProcessorInterface $extensionAttributesJoinProcessor;

    /**
     * @param resource                           $resource
     * @param HokodoCustomerInterfaceFactory     $hokodoCustomerFactory
     * @param HokodoCustomerFactory              $hokodoCustomerModelFactory
     * @param HokodoCustomerCollectionFactory    $hokodoCustomerCollectionFactory
     * @param HokodoCustomerCollectionDevFactory $hokodoCustomerCollectionDevFactory
     * @param CollectionProcessorInterface       $collectionProcessor
     * @param JoinProcessorInterface             $extensionAttributesJoinProcessor
     * @param SearchResultsFactory               $searchResultsFactory
     * @param SerializerInterface                $serializer
     * @param DataObjectHelper                   $dataObjectHelper
     * @param ResourceDev                        $resourceDev
     * @param Config                             $config
     * @param Env                                $envSource
     */
    public function __construct(
        Resource $resource,
        HokodoCustomerInterfaceFactory $hokodoCustomerFactory,
        HokodoCustomerFactory $hokodoCustomerModelFactory,
        HokodoCustomerCollectionFactory $hokodoCustomerCollectionFactory,
        HokodoCustomerCollectionDevFactory $hokodoCustomerCollectionDevFactory,
        CollectionProcessorInterface $collectionProcessor,
        JoinProcessorInterface $extensionAttributesJoinProcessor,
        SearchResultsFactory $searchResultsFactory,
        SerializerInterface $serializer,
        DataObjectHelper $dataObjectHelper,
        ResourceDev $resourceDev,
        Config $config,
        Env $envSource
    ) {
        $this->resource = $resource;
        $this->hokodoCustomerFactory = $hokodoCustomerFactory;
        $this->hokodoCustomerCollectionFactory = $hokodoCustomerCollectionFactory;
        $this->hokodoCustomerCollectionDevFactory = $hokodoCustomerCollectionDevFactory;
        $this->collectionProcessor = $collectionProcessor;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->serializer = $serializer;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->hokodoCustomerModelFactory = $hokodoCustomerModelFactory;
        $this->resourceDev = $resourceDev;
        $this->config = $config;
        $this->envSource = $envSource;

        $this->extensionAttributesJoinProcessor = $extensionAttributesJoinProcessor;
    }

    /**
     * @inheritDoc
     *
     * @throws CouldNotSaveException
     */
    public function save(HokodoCustomerInterface $hokodoApiCustomer): HokodoCustomerInterface
    {
        /* @var HokodoCustomer $customerModel */
        $customerModel = $this->hokodoCustomerModelFactory->create(['data' => $hokodoApiCustomer->getData()]);
        if ($creditLimit = $hokodoApiCustomer->getCreditLimit()) {
            $customerModel->setData(HokodoCustomerInterface::CREDIT_LIMIT, $creditLimit->toJson());
        }
        try {
            if ($this->config->getEnvironment() !== Config::ENV_PRODUCTION) {
                $this->resourceDev->save(
                    $customerModel->setData('env', $this->envSource->getEnvId($this->config->getEnvironment()))
                );
            } else {
                $this->resource->save($customerModel);
            }
            $savedCustomer = $this->getByCustomerId($hokodoApiCustomer->getCustomerId());
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__($exception->getMessage()));
        }

        return $savedCustomer;
    }

    /**
     * @inheritDoc
     *
     * @throws CouldNotDeleteException
     */
    public function delete(HokodoCustomerInterface $hokodoCustomer): bool
    {
        /* @var HokodoCustomer $customerModel */
        $customerModel = $this->hokodoCustomerModelFactory->create();
        $customerModel->setData($hokodoCustomer->getData());
        try {
            if ($this->config->getEnvironment() !== Config::ENV_PRODUCTION) {
                $this->resourceDev->delete(
                    $customerModel->setData('env', $this->envSource->getEnvId($this->config->getEnvironment()))
                );
            } else {
                $this->resource->delete($customerModel);
            }
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__($exception->getMessage()));
        }

        return true;
    }

    /**
     * @inheritDoc
     */
    public function getByCustomerId(int $customerId): HokodoCustomerInterface
    {
        /* @var HokodoCustomer $customerModel */
        $customerModel = $this->hokodoCustomerModelFactory->create();
        if ($this->config->getEnvironment() !== Config::ENV_PRODUCTION) {
            $this->resourceDev->load(
                $customerModel->setData('env', $this->envSource->getEnvId($this->config->getEnvironment())),
                $customerId,
                HokodoCustomerInterface::CUSTOMER_ID
            );
        } else {
            $this->resource->load($customerModel, $customerId, HokodoCustomerInterface::CUSTOMER_ID);
        }

        return $this->populateDataObject($customerModel);
    }

    /**
     * @inheritDoc
     */
    public function getByEntityId(int $entityId): HokodoCustomerInterface
    {
        return $this->getByCustomerId($entityId);
    }

    /**
     * Populate data model object from model data.
     *
     * @param HokodoCustomer $customerModel
     *
     * @return HokodoCustomerInterface
     */
    public function populateDataObject(HokodoCustomer $customerModel): HokodoCustomerInterface
    {
        $customerDO = $this->hokodoCustomerFactory->create();
        if ($creditLimitJson = $customerModel->getData(HokodoCustomerInterface::CREDIT_LIMIT)) {
            $customerModel->setData(
                HokodoCustomerInterface::CREDIT_LIMIT,
                $this->serializer->unserialize($creditLimitJson)
            );
        }
        $this->dataObjectHelper->populateWithArray(
            $customerDO,
            $customerModel->getData(),
            HokodoCustomerInterface::class
        );

        return $customerDO;
    }

    /**
     * Get List.
     *
     * @param SearchCriteriaInterface $searchCriteria
     *
     * @return SearchResults
     */
    public function getList(SearchCriteriaInterface $searchCriteria): SearchResults
    {
        $collection = $this->hokodoCustomerCollectionFactory->create();
        if ($this->config->getEnvironment() !== Config::ENV_PRODUCTION) {
            $collection = $this->hokodoCustomerCollectionDevFactory->create();
            $collection->addEnvFilter($this->envSource->getEnvId($this->config->getEnvironment()));
        }
        $this->collectionProcessor->process($searchCriteria, $collection);
        $this->extensionAttributesJoinProcessor->process($collection);
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);
        $searchResults->setItems($this->populateCollectionWithArray($collection->getItems()));
        $searchResults->setTotalCount($collection->getSize());

        return $searchResults;
    }

    /**
     * Populate Collection with array.
     *
     * @param array $collectionItems
     *
     * @return array
     */
    private function populateCollectionWithArray(array $collectionItems): array
    {
        $items = [];
        foreach ($collectionItems as $item) {
            $customerModel = $this->hokodoCustomerModelFactory->create();
            $customerModel->setData($item->getData());
            $items[] = $this->populateDataObject($customerModel);
        }
        return $items;
    }

    /**
     * @inheritdoc
     *
     * @throws CouldNotSaveException
     */
    public function saveHokodoEntity(HokodoEntityInterface $hokodoEntity): void
    {
        if ($hokodoEntity instanceof HokodoCustomerInterface) {
            $this->save($hokodoEntity);
        }
    }
}
