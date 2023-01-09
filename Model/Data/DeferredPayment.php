<?php
/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

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
    public function setUrl($url)
    {
        return $this->setData(self::URL, $url);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\DeferredPaymentInterface::getUrl()
     */
    public function getUrl()
    {
        return $this->_get(self::URL);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\DeferredPaymentInterface::setId()
     */
    public function setId($id)
    {
        return $this->setData(self::ID, $id);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\DeferredPaymentInterface::getId()
     */
    public function getId()
    {
        return $this->_get(self::ID);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\DeferredPaymentInterface::setNumber()
     */
    public function setNumber($number)
    {
        return $this->setData(self::NUMBER, $number);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\DeferredPaymentInterface::getNumber()
     */
    public function getNumber()
    {
        return $this->_get(self::NUMBER);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\DeferredPaymentInterface::setPaymentPlan()
     */
    public function setPaymentPlan($paymentPlan)
    {
        return $this->setData(self::PAYMENT_PLAN, $paymentPlan);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\DeferredPaymentInterface::getPaymentPlan()
     */
    public function getPaymentPlan()
    {
        return $this->_get(self::PAYMENT_PLAN);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\DeferredPaymentInterface::setOrder()
     */
    public function setOrder($order)
    {
        return $this->setData(self::ORDER, $order);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\DeferredPaymentInterface::getOrder()
     */
    public function getOrder()
    {
        return $this->_get(self::ORDER);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\DeferredPaymentInterface::setStatus()
     */
    public function setStatus($status)
    {
        return $this->setData(self::STATUS, $status);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\DeferredPaymentInterface::getStatus()
     */
    public function getStatus()
    {
        return $this->_get(self::STATUS);
    }
}
