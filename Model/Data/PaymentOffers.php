<?php
/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

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
    public function getId(): ?string
    {
        return $this->_get(self::ID);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\PaymentOffersInterface::getOfferedPaymentPlans()
     */
    public function getOfferedPaymentPlans(): ?array
    {
        return $this->_get(self::OFFERED_PAYMENT_PLANS);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\PaymentOffersInterface::getUrl()
     */
    public function getUrl(): ?string
    {
        return $this->_get(self::URL);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\PaymentOffersInterface::getOrder()
     */
    public function getOrder(): ?string
    {
        return $this->_get(self::ORDER);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\PaymentOffersInterface::setId()
     */
    public function setId(?string $id): PaymentOffersInterface
    {
        return $this->setData(self::ID, $id);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\PaymentOffersInterface::setOfferedPaymentPlans()
     */
    public function setOfferedPaymentPlans(?array $offeredPaymentPlans): PaymentOffersInterface
    {
        return $this->setData(self::OFFERED_PAYMENT_PLANS, $offeredPaymentPlans);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\PaymentOffersInterface::setUrl()
     */
    public function setUrl(?string $url): PaymentOffersInterface
    {
        return $this->setData(self::URL, $url);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\PaymentOffersInterface::setOrder()
     */
    public function setOrder(?string $order): PaymentOffersInterface
    {
        return $this->setData(self::ORDER, $order);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\PaymentOffersInterface::getLegals()
     */
    public function getLegals(): ?array
    {
        return $this->_get(self::LEGALS);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\PaymentOffersInterface::getLocale()
     */
    public function getLocale(): ?string
    {
        return $this->_get(self::LOCALE);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\PaymentOffersInterface::getMetadata()
     */
    public function getMetadata(): ?array
    {
        return $this->_get(self::METADATA);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\PaymentOffersInterface::getUrls()
     */
    public function getUrls(): ?array
    {
        return $this->_get(self::URLS);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\PaymentOffersInterface::setLegals()
     */
    public function setLegals(?array $legals): PaymentOffersInterface
    {
        return $this->setData(self::LEGALS, $legals);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\PaymentOffersInterface::setLocale()
     */
    public function setLocale(?string $locale): PaymentOffersInterface
    {
        return $this->setData(self::LOCALE, $locale);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\PaymentOffersInterface::setMetadata()
     */
    public function setMetadata(?array $metadata): PaymentOffersInterface
    {
        return $this->setData(self::METADATA, $metadata);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\PaymentOffersInterface::setUrls()
     */
    public function setUrls(?array $urls): PaymentOffersInterface
    {
        return $this->setData(self::URLS, $urls);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\PaymentOffersInterface::getIsEligible()
     */
    public function getIsEligible(): bool
    {
        return (bool) $this->_get(self::IS_ELIGIBLE);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\PaymentOffersInterface::setIsEligible()
     */
    public function setIsEligible(?bool $isEligible): PaymentOffersInterface
    {
        return $this->setData(self::IS_ELIGIBLE, $isEligible);
    }
}
