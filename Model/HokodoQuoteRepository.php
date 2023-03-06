<?php
/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
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
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Quote\Api\CartRepositoryInterface;

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
     * @var CartRepositoryInterface
     */
    private $cartRepository;

    /**
     * @param QuoteResource               $resource
     * @param HokodoQuoteInterfaceFactory $quoteFactory
     * @param CartRepositoryInterface     $cartRepository
     */
    public function __construct(
        QuoteResource $resource,
        HokodoQuoteInterfaceFactory $quoteFactory,
        CartRepositoryInterface $cartRepository
    ) {
        $this->resource = $resource;
        $this->quoteFactory = $quoteFactory;
        $this->cartRepository = $cartRepository;
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

    /**
     * @inheritDoc
     *
     * @throws CouldNotDeleteException
     * @throws NoSuchEntityException
     */
    public function deleteByCustomerId(int $customerId): void
    {
        $cart = $this->cartRepository->getActiveForCustomer($customerId);
        if ($cart->getId()) {
            $hokodoQuote = $this->getByQuoteId($cart->getId());
            if ($hokodoQuote->getQuoteId()) {
                $this->deleteByQuoteId($cart->getId());
            }
        }
    }
}
