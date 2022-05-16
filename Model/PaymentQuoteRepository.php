<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Model;

use Hokodo\BNPL\Api\Data\PaymentQuoteInterface;
use Hokodo\BNPL\Api\Data\PaymentQuoteInterfaceFactory;
use Hokodo\BNPL\Api\PaymentQuoteRepositoryInterface;
use Hokodo\BNPL\Model\ResourceModel\PaymentQuote as PaymentQuoteResource;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Class Hokodo\BNPL\Model\PaymentQuoteRepository.
 */
class PaymentQuoteRepository implements PaymentQuoteRepositoryInterface
{
    /**
     * @var PaymentQuoteResource
     */
    private $resource;

    /**
     * @var PaymentQuoteInterfaceFactory
     */
    private $paymentQuoteFactory;

    /**
     * @var array
     */
    private $instancesById = [];

    /**
     * @var array
     */
    private $instances = [];

    /**
     * @param PaymentQuoteResource         $resource
     * @param PaymentQuoteInterfaceFactory $paymentQuoteFactory
     */
    public function __construct(
        PaymentQuoteResource $resource,
        PaymentQuoteInterfaceFactory $paymentQuoteFactory
    ) {
        $this->resource = $resource;
        $this->paymentQuoteFactory = $paymentQuoteFactory;
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\PaymentQuoteRepositoryInterface::getById()
     */
    public function getById($paymentQuoteId)
    {
        if (!isset($this->instancesById[$paymentQuoteId])) {
            /**
             * @var PaymentQuoteInterface $paymentQuote
             */
            $paymentQuote = $this->paymentQuoteFactory->create();
            $this->resource->load($paymentQuote, $paymentQuoteId);
            if (!$paymentQuote->getId()) {
                throw NoSuchEntityException::singleField('payment_quote_id', $paymentQuoteId);
            }

            $this->cacheInstance($paymentQuote);
        }

        return $this->instancesById[$paymentQuoteId];
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\PaymentQuoteRepositoryInterface::getByQuoteId()
     */
    public function getByQuoteId($quoteId)
    {
        if (!isset($this->instances[$quoteId])) {
            $paymentQuoteId = $this->resource->getByQuoteId($quoteId);
            return $this->getById($paymentQuoteId);
        }
        return $this->instances[$quoteId];
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\PaymentQuoteRepositoryInterface::getByOrderId()
     */
    public function getByOrderId($orderId)
    {
        $paymentQuoteId = $this->resource->getByOrderId($orderId);
        return $this->getById($paymentQuoteId);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\PaymentQuoteRepositoryInterface::save()
     */
    public function save(PaymentQuoteInterface $paymentQuote)
    {
        try {
            return $this->resource->save($paymentQuote);
        } catch (\Exception $e) {
            throw new CouldNotSaveException(__($e->getMessage()));
        }

        return $paymentQuote;
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\PaymentQuoteRepositoryInterface::delete()
     */
    public function delete(PaymentQuoteInterface $paymentQuote)
    {
        try {
            $this->resource->delete($paymentQuote);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__($exception->getMessage()));
        }

        return true;
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\PaymentQuoteRepositoryInterface::deleteById()
     */
    public function deleteById($paymentQuoteId)
    {
        return $this->delete($this->getById($paymentQuoteId));
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\PaymentQuoteRepositoryInterface::deleteByQuoteId()
     */
    public function deleteByQuoteId($quoteId)
    {
        return $this->delete($this->getByQuoteId($quoteId));
    }

    /**
     * A function cache instance.
     *
     * @param PaymentQuoteInterface $paymentQuote
     */
    private function cacheInstance(PaymentQuoteInterface $paymentQuote)
    {
        $this->instancesById[$paymentQuote->getId()] = $paymentQuote;
        $this->instances[$paymentQuote->getQuoteId()] = $paymentQuote;
    }
}
