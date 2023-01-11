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
use Hokodo\BNPL\Model\ResourceModel\HokodoCustomer as Resource;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;

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
     * @param resource                       $resource
     * @param HokodoCustomerInterfaceFactory $hokodoCustomerFactory
     */
    public function __construct(
        Resource $resource,
        HokodoCustomerInterfaceFactory $hokodoCustomerFactory
    ) {
        $this->resource = $resource;
        $this->hokodoCustomerFactory = $hokodoCustomerFactory;
    }

    /**
     * @inheritDoc
     *
     * @throws CouldNotSaveException
     */
    public function save(HokodoCustomerInterface $hokodoCustomer): HokodoCustomerInterface
    {
        try {
            $this->resource->save($hokodoCustomer);
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
        try {
            $this->resource->delete($hokodoCustomer);
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
        /* @var HokodoCustomerInterface $hokodoCustomer */
        $hokodoCustomer = $this->hokodoCustomerFactory->create();
        $this->resource->load($hokodoCustomer, $customerId, HokodoCustomerInterface::CUSTOMER_ID);

        return $hokodoCustomer;
    }

    /**
     * Alias for getByCustomerId.
     *
     * @param int $entityId
     *
     * @return HokodoEntityInterface
     */
    public function getById(int $entityId): HokodoEntityInterface
    {
        return $this->getByCustomerId($entityId);
    }
}
