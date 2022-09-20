<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Hokodo\BNPL\Model;

use Hokodo\BNPL\Api\Data\HokodoDataWebApiInterface;
use Hokodo\BNPL\Api\Data\HokodoDataWebApiInterfaceFactory;
use Hokodo\BNPL\Api\HokodoDataWebApiRepositoryInterface;
use Hokodo\BNPL\Model\ResourceModel\HokodoDataWebApi as Resource;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;

class HokodoDataWebApiRepository implements HokodoDataWebApiRepositoryInterface
{
    /**
     * @var Resource
     */
    private $resource;

    /**
     * @var HokodoDataWebApiInterfaceFactory
     */
    private $dataFactory;

    /**
     * @param Resource                         $resource
     * @param HokodoDataWebApiInterfaceFactory $dataFactory
     */
    public function __construct(
        Resource $resource,
        HokodoDataWebApiInterfaceFactory $dataFactory
    ) {
        $this->resource = $resource;
        $this->dataFactory = $dataFactory;
    }

    /**
     * @inheritDoc
     *
     * @throws CouldNotSaveException
     */
    public function save(HokodoDataWebApiInterface $hokodoDataWebApi): HokodoDataWebApiInterface
    {
        try {
            $this->resource->save($hokodoDataWebApi);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__($exception->getMessage()));
        }
        return $hokodoDataWebApi;
    }

    /**
     * @inheritDoc
     *
     * @throws CouldNotDeleteException
     */
    public function delete(HokodoDataWebApiInterface $hokodoDataWebApi): bool
    {
        try {
            $this->resource->delete($hokodoDataWebApi);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__($exception->getMessage()));
        }

        return true;
    }

    /**
     * @inheritDoc
     */
    public function getByCustomerId($customerId): HokodoDataWebApiInterface
    {
        /* @var HokodoDataWebApiInterface $hokodoDataWebApi */
        $hokodoDataWebApi = $this->quoteFactory->create();
        $this->resource->load($hokodoDataWebApi, (int) $customerId, HokodoDataWebApiInterface::QUOTE_ID);

        return $hokodoDataWebApi;
    }
}
