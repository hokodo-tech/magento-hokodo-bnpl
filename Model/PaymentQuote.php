<?php
/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Model;

use Hokodo\BNPL\Api\Data\PaymentQuoteInterface;
use Magento\Framework\Model\AbstractModel;

/**
 * Class Hokodo\BNPL\Model\PaymentQuote.
 */
class PaymentQuote extends AbstractModel implements PaymentQuoteInterface
{
    /**
     * @inheritDoc
     *
     * @see \Magento\Framework\Model\AbstractModel::_construct()
     */
    protected function _construct()
    {
        $this->_init(\Hokodo\BNPL\Model\ResourceModel\PaymentQuote::class);
    }

    /**
     * @inheritDoc
     *
     * @see \Magento\Framework\Model\AbstractModel::setId()
     */
    public function setId($id)
    {
        return $this->setPaymentQuoteId($id);
    }

    /**
     * @inheritDoc
     *
     * @see \Magento\Framework\Model\AbstractModel::getId()
     */
    public function getId()
    {
        return $this->getPaymentQuoteId();
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\PaymentQuoteInterface::setPaymentQuoteId()
     */
    public function setPaymentQuoteId($paymentQuoteId)
    {
        return $this->setData(self::PAYMENT_QUOTE_ID, $paymentQuoteId);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\PaymentQuoteInterface::getPaymentQuoteId()
     */
    public function getPaymentQuoteId()
    {
        return $this->getData(self::PAYMENT_QUOTE_ID);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\PaymentQuoteInterface::setQuoteId()
     */
    public function setQuoteId($quoteId)
    {
        return $this->setData(self::QUOTE_ID, $quoteId);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\PaymentQuoteInterface::getQuoteId()
     */
    public function getQuoteId()
    {
        return $this->getData(self::QUOTE_ID);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\PaymentQuoteInterface::setUserId()
     */
    public function setUserId($userId)
    {
        return $this->setData(self::USER_ID, $userId);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\PaymentQuoteInterface::getUserId()
     */
    public function getUserId()
    {
        return $this->getData(self::USER_ID);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\PaymentQuoteInterface::setOrganisationId()
     */
    public function setOrganisationId($organisationId)
    {
        return $this->setData(self::ORGANISATION_ID, $organisationId);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\PaymentQuoteInterface::getOrganisationId()
     */
    public function getOrganisationId()
    {
        return $this->getData(self::ORGANISATION_ID);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\PaymentQuoteInterface::setOrderId()
     */
    public function setOrderId($orderId)
    {
        return $this->setData(self::ORDER_ID, $orderId);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\PaymentQuoteInterface::getOrderId()
     */
    public function getOrderId()
    {
        return $this->getData(self::ORDER_ID);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\PaymentQuoteInterface::setOfferId()
     */
    public function setOfferId($offerId)
    {
        return $this->setData(self::OFFER_ID, $offerId);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\PaymentQuoteInterface::getOfferId()
     */
    public function getOfferId()
    {
        return $this->getData(self::OFFER_ID);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\PaymentQuoteInterface::setDeferredPaymentId()
     */
    public function setDeferredPaymentId($deferredPaymentId)
    {
        return $this->setData(self::DEFERRED_PAYMENT_ID, $deferredPaymentId);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\PaymentQuoteInterface::getDeferredPaymentId()
     */
    public function getDeferredPaymentId()
    {
        return $this->getData(self::DEFERRED_PAYMENT_ID);
    }

    /**
     * A function rest api data.
     *
     * @return \Hokodo\BNPL\Model\PaymentQuote
     */
    public function resetApiData()
    {
        $this->setUserId(null);
        $this->setOrganisationId(null);
        $this->setOrderId(null);
        $this->setOfferId(null);
        $this->setDeferredPaymentId(null);

        return $this;
    }
}
