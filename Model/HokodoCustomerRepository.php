<?php
/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Hokodo\BNPL\Model;

use Hokodo\BNPL\Api\Data\HokodoCustomerInterface;
use Hokodo\BNPL\Api\Data\HokodoCustomerInterfaceFactory;
use Hokodo\BNPL\Api\HokodoCustomerRepositoryInterface;
use Hokodo\BNPL\Model\ResourceModel\HokodoCustomer as Resource;
use Magento\Framework\Api\DataObjectHelper;
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
     * @param resource                       $resource
     * @param HokodoCustomerInterfaceFactory $hokodoCustomerFactory
     * @param HokodoCustomerFactory          $hokodoCustomerModelFactory
     * @param SerializerInterface            $serializer
     * @param DataObjectHelper               $dataObjectHelper
     */
    public function __construct(
        Resource $resource,
        HokodoCustomerInterfaceFactory $hokodoCustomerFactory,
        HokodoCustomerFactory $hokodoCustomerModelFactory,
        SerializerInterface $serializer,
        DataObjectHelper $dataObjectHelper
    ) {
        $this->resource = $resource;
        $this->hokodoCustomerFactory = $hokodoCustomerFactory;
        $this->serializer = $serializer;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->hokodoCustomerModelFactory = $hokodoCustomerModelFactory;
    }

    /**
     * @inheritDoc
     *
     * @throws CouldNotSaveException
     */
    public function save(HokodoCustomerInterface $hokodoCustomer): HokodoCustomerInterface
    {
        /* @var HokodoCustomer $customerModel */
        $customerModel = $this->hokodoCustomerModelFactory->create();
        if ($id = $hokodoCustomer->getId()) {
            $this->resource->load($customerModel, $id);
        }
        $customerModel->setData($hokodoCustomer->getData());
        if ($creditLimit = $hokodoCustomer->getCreditLimit()) {
            $customerModel->setData(HokodoCustomerInterface::CREDIT_LIMIT, $creditLimit->toJson());
        }
        try {
            $this->resource->save($customerModel);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__($exception->getMessage()));
        }

        return $hokodoCustomer;
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
            $this->resource->delete($customerModel);
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
        $this->resource->load($customerModel, $customerId, HokodoCustomerInterface::CUSTOMER_ID);

        return $this->populateDataObject($customerModel);
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
                HokodoCustomerInterface::CREDIT_LIMIT, $this->serializer->unserialize($creditLimitJson)
            );
        }
        $this->dataObjectHelper->populateWithArray(
            $customerDO,
            $customerModel->getData(),
            HokodoCustomerInterface::class
        );

        return $customerDO;
    }
}
