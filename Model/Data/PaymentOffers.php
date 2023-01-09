<?php
/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Model\Data;

use Hokodo\BNPL\Api\Data\PaymentOffersInterface;
use Magento\Framework\Api\AbstractSimpleObject;

/**
 * Class Hokodo\BNPL\Model\Data\PaymentOffers.
 */
class PaymentOffers extends AbstractSimpleObject implements PaymentOffersInterface
{
    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\PaymentOffersInterface::getId()
     */
    public function getId()
    {
        return $this->_get(self::ID);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\PaymentOffersInterface::getOfferedPaymentPlans()
     */
    public function getOfferedPaymentPlans()
    {
        return $this->_get(self::OFFERED_PAYMENT_PLANS);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\PaymentOffersInterface::getUrl()
     */
    public function getUrl()
    {
        return $this->_get(self::URL);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\PaymentOffersInterface::getOrder()
     */
    public function getOrder()
    {
        return $this->_get(self::ORDER);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\PaymentOffersInterface::setId()
     */
    public function setId($id)
    {
        return $this->setData(self::ID, $id);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\PaymentOffersInterface::setOfferedPaymentPlans()
     */
    public function setOfferedPaymentPlans(array $offeredPaymentPlans)
    {
        return $this->setData(self::OFFERED_PAYMENT_PLANS, $offeredPaymentPlans);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\PaymentOffersInterface::setUrl()
     */
    public function setUrl($url)
    {
        return $this->setData(self::URL, $url);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\PaymentOffersInterface::setOrder()
     */
    public function setOrder($order)
    {
        return $this->setData(self::ORDER, $order);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\PaymentOffersInterface::getLegals()
     */
    public function getLegals()
    {
        return $this->_get(self::LEGALS);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\PaymentOffersInterface::getLocale()
     */
    public function getLocale()
    {
        return $this->_get(self::LOCALE);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\PaymentOffersInterface::getMetadata()
     */
    public function getMetadata()
    {
        return $this->_get(self::METADATA);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\PaymentOffersInterface::getUrls()
     */
    public function getUrls()
    {
        return $this->_get(self::URLS);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\PaymentOffersInterface::setLegals()
     */
    public function setLegals(array $legals)
    {
        return $this->setData(self::LEGALS, $legals);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\PaymentOffersInterface::setLocale()
     */
    public function setLocale($locale)
    {
        return $this->setData(self::LOCALE, $locale);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\PaymentOffersInterface::setMetadata()
     */
    public function setMetadata($metadata)
    {
        return $this->setData(self::METADATA, $metadata);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\PaymentOffersInterface::setUrls()
     */
    public function setUrls(array $urls)
    {
        return $this->setData(self::URLS, $urls);
    }
}
