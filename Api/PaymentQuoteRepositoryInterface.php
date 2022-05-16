<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Api;

/**
 * Interface Hokodo\BNPL\Api\PaymentQuoteRepositoryInterface.
 */
interface PaymentQuoteRepositoryInterface
{
    /**
     * A function that gets by id.
     *
     * @param int $paymentQuoteId
     *
     * @return \Hokodo\BNPL\Api\Data\PaymentQuoteInterface
     *
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById($paymentQuoteId);

    /**
     * A function that gets by quote id.
     *
     * @param string $quoteId
     *
     * @return \Hokodo\BNPL\Api\Data\PaymentQuoteInterface
     *
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getByQuoteId($quoteId);

    /**
     * A function that gets by order id.
     *
     * @param string $orderId
     *
     * @return \Hokodo\BNPL\Api\Data\PaymentQuoteInterface
     *
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getByOrderId($orderId);

    /**
     * A function that save payment quote interface.
     *
     * @param \Hokodo\BNPL\Api\Data\PaymentQuoteInterface $paymentQuote
     *
     * @return \Hokodo\BNPL\Api\Data\PaymentQuoteInterface
     *
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function save(Data\PaymentQuoteInterface $paymentQuote);

    /**
     * A function that delete interface.
     *
     * @param \Hokodo\BNPL\Api\Data\PaymentQuoteInterface $paymentQuote
     *
     * @return bool true on success
     *
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function delete(Data\PaymentQuoteInterface $paymentQuote);

    /**
     * A function that removes by id.
     *
     * @param int $paymentQuoteId
     *
     * @return bool true on success
     *
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function deleteById($paymentQuoteId);

    /**
     * A function that removes by quote id.
     *
     * @param int $quoteId
     *
     * @return bool true on success
     *
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function deleteByQuoteId($quoteId);
}
