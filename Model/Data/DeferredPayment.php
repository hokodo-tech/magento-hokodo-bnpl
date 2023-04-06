<?php
/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Hokodo\BNPL\Model\Data;

use Hokodo\BNPL\Api\Data\DeferredPaymentInterface;
use Magento\Framework\Api\AbstractSimpleObject;

/**
 * Class Hokodo\BNPL\Model\Data\DeferredPayment.
 */
class DeferredPayment extends AbstractSimpleObject implements DeferredPaymentInterface
{
    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\DeferredPaymentInterface::setUrl()
     */
    public function setUrl(?string $url): DeferredPaymentInterface
    {
        return $this->setData(self::URL, $url);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\DeferredPaymentInterface::getUrl()
     */
    public function getUrl(): ?string
    {
        return $this->_get(self::URL);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\DeferredPaymentInterface::setId()
     */
    public function setId(?string $id): DeferredPaymentInterface
    {
        return $this->setData(self::ID, $id);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\DeferredPaymentInterface::getId()
     */
    public function getId(): ?string
    {
        return $this->_get(self::ID);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\DeferredPaymentInterface::setNumber()
     */
    public function setNumber(?string $number): DeferredPaymentInterface
    {
        return $this->setData(self::NUMBER, $number);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\DeferredPaymentInterface::getNumber()
     */
    public function getNumber(): ?string
    {
        return $this->_get(self::NUMBER);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\DeferredPaymentInterface::setPaymentPlan()
     */
    public function setPaymentPlan(?string $paymentPlan): DeferredPaymentInterface
    {
        return $this->setData(self::PAYMENT_PLAN, $paymentPlan);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\DeferredPaymentInterface::getPaymentPlan()
     */
    public function getPaymentPlan(): ?string
    {
        return $this->_get(self::PAYMENT_PLAN);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\DeferredPaymentInterface::setOrder()
     */
    public function setOrder(?string $order): DeferredPaymentInterface
    {
        return $this->setData(self::ORDER, $order);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\DeferredPaymentInterface::getOrder()
     */
    public function getOrder(): ?string
    {
        return $this->_get(self::ORDER);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\DeferredPaymentInterface::setStatus()
     */
    public function setStatus(?string $status): DeferredPaymentInterface
    {
        return $this->setData(self::STATUS, $status);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\DeferredPaymentInterface::getStatus()
     */
    public function getStatus(): ?string
    {
        return $this->_get(self::STATUS);
    }
}
