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
use Hokodo\BNPL\Gateway\Config\Config;
use Hokodo\BNPL\Model\Config\Source\Env;
use Hokodo\BNPL\Model\ResourceModel\HokodoQuote as QuoteResource;
use Hokodo\BNPL\Model\ResourceModel\HokodoQuoteDev as QuoteResourceDev;
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
     * @var Config
     */
    private Config $config;

    /**
     * @var QuoteResourceDev
     */
    private QuoteResourceDev $resourceDev;

    /**
     * @var Env
     */
    private Env $envSource;

    /**
     * @param QuoteResource               $resource
     * @param QuoteResourceDev            $resourceDev
     * @param HokodoQuoteInterfaceFactory $quoteFactory
     * @param CartRepositoryInterface     $cartRepository
     * @param Config                      $config
     * @param Env                         $envSource
     */
    public function __construct(
        QuoteResource $resource,
        QuoteResourceDev $resourceDev,
        HokodoQuoteInterfaceFactory $quoteFactory,
        CartRepositoryInterface $cartRepository,
        Config $config,
        Env $envSource
    ) {
        $this->resource = $resource;
        $this->resourceDev = $resourceDev;
        $this->quoteFactory = $quoteFactory;
        $this->cartRepository = $cartRepository;
        $this->config = $config;
        $this->envSource = $envSource;
    }

    /**
     * @inheritDoc
     *
     * @throws CouldNotSaveException
     */
    public function save(HokodoQuoteInterface $hokodoQuote): HokodoQuoteInterface
    {
        try {
            if ($this->config->getEnvironment() !== Config::ENV_PRODUCTION) {
                $this->resourceDev->save(
                    $hokodoQuote->setData('env', $this->envSource->getEnvId($this->config->getEnvironment()))
                );
            } else {
                $this->resource->save($hokodoQuote);
            }
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
            if ($this->config->getEnvironment() !== Config::ENV_PRODUCTION) {
                $this->resourceDev->delete(
                    $hokodoQuote->setData('env', $this->envSource->getEnvId($this->config->getEnvironment()))
                );
            } else {
                $this->resource->delete($hokodoQuote);
            }
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
        if ($this->config->getEnvironment() !== Config::ENV_PRODUCTION) {
            $this->resourceDev->load(
                $hokodoQuote->setData('env', $this->envSource->getEnvId($this->config->getEnvironment())),
                (int) $quoteId,
                HokodoQuoteInterface::QUOTE_ID
            );
        } else {
            $this->resource->load($hokodoQuote, (int) $quoteId, HokodoQuoteInterface::QUOTE_ID);
        }

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
