<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Hokodo\BNPL\Model;

use Hokodo\BNPL\Api\Data\HokodoQuoteInterface;
use Hokodo\BNPL\Api\Data\HokodoQuoteInterfaceFactory;
use Hokodo\BNPL\Api\HokodoQuoteRepositoryInterface;
use Hokodo\BNPL\Model\ResourceModel\HokodoQuote as QuoteResource;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;

class HokodoQuoteRepository implements HokodoQuoteRepositoryInterface
{
    /**
     * @var QuoteResource
     */
    private $resource;

    /**
     * @var HokodoQuoteInterfaceFactory
     */
    private $quoteFactory;

    /**
     * @param QuoteResource               $resource
     * @param HokodoQuoteInterfaceFactory $quoteFactory
     */
    public function __construct(
        QuoteResource $resource,
        HokodoQuoteInterfaceFactory $quoteFactory
    ) {
        $this->resource = $resource;
        $this->quoteFactory = $quoteFactory;
    }

    /**
     * @inheritDoc
     *
     * @throws CouldNotSaveException
     */
    public function save(HokodoQuoteInterface $hokodoQuote): HokodoQuoteInterface
    {
        try {
            $this->resource->save($hokodoQuote);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__($exception->getMessage()));
        }
        return $hokodoQuote;
    }

    /**
     * @inheritDoc
     *
     * @throws CouldNotDeleteException
     */
    public function delete(HokodoQuoteInterface $hokodoQuote): bool
    {
        try {
            $this->resource->delete($hokodoQuote);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__($exception->getMessage()));
        }

        return true;
    }

    /**
     * @inheritDoc
     */
    public function getByQuoteId($quoteId): HokodoQuoteInterface
    {
        /* @var HokodoQuoteInterface $hokodoQuote */
        $hokodoQuote = $this->quoteFactory->create();
        $this->resource->load($hokodoQuote, (int) $quoteId, HokodoQuoteInterface::QUOTE_ID);

        return $hokodoQuote;
    }

    /**
     * @inheritDoc
     *
     * @throws CouldNotDeleteException
     */
    public function deleteByQuoteId($quoteId): bool
    {
        return $this->delete($this->getByQuoteId($quoteId));
    }
}
