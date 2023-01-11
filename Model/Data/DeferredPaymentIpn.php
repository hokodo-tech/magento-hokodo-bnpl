<?php
/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Hokodo\BNPL\Model\Data;

use Hokodo\BNPL\Api\Data\DeferredPaymentIpnInterface;
use Magento\Framework\Api\AbstractSimpleObject;

class DeferredPaymentIpn extends AbstractSimpleObject implements DeferredPaymentIpnInterface
{
    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\DeferredPaymentIpnInterface::setUrl()
     */
    public function setUrl($url)
    {
        return $this->setData(self::URL, $url);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\DeferredPaymentIpnInterface::getUrl()
     */
    public function getUrl()
    {
        return $this->_get(self::URL);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\DeferredPaymentIpnInterface::setId()
     */
    public function setId($id)
    {
        return $this->setData(self::ID, $id);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\DeferredPaymentIpnInterface::getId()
     */
    public function getId()
    {
        return $this->_get(self::ID);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\DeferredPaymentIpnInterface::setNumber()
     */
    public function setNumber($number)
    {
        return $this->setData(self::NUMBER, $number);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\DeferredPaymentIpnInterface::getNumber()
     */
    public function getNumber()
    {
        return $this->_get(self::NUMBER);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\DeferredPaymentIpnInterface::setPaymentPlan()
     */
    public function setPaymentPlan(\Hokodo\BNPL\Api\Data\PaymentPlanInterface $paymentPlan)
    {
        return $this->setData(self::PAYMENT_PLAN, $paymentPlan);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\DeferredPaymentIpnInterface::getPaymentPlan()
     */
    public function getPaymentPlan()
    {
        return $this->_get(self::PAYMENT_PLAN);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\DeferredPaymentIpnInterface::setOrder()
     */
    public function setOrder($order)
    {
        return $this->setData(self::ORDER, $order);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\DeferredPaymentIpnInterface::getOrder()
     */
    public function getOrder()
    {
        return $this->_get(self::ORDER);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\DeferredPaymentIpnInterface::setStatus()
     */
    public function setStatus($status)
    {
        return $this->setData(self::STATUS, $status);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\DeferredPaymentIpnInterface::getStatus()
     */
    public function getStatus()
    {
        return $this->_get(self::STATUS);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\DeferredPaymentIpnInterface::getNextPaymentDate()
     */
    public function getNextPaymentDate()
    {
        return $this->_get(self::NEXT_PAYMENT_DATE);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\DeferredPaymentIpnInterface::getOutstandingBalance()
     */
    public function getOutstandingBalance()
    {
        return $this->_get(self::OUTSTANDING_BALANCE);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\DeferredPaymentIpnInterface::getPayments()
     */
    public function getPayments()
    {
        return $this->_get(self::PAYMENTS);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\DeferredPaymentIpnInterface::setNextPaymentDate()
     */
    public function setNextPaymentDate($nextPaymentDate)
    {
        return $this->setData(self::NEXT_PAYMENT_DATE, $nextPaymentDate);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\DeferredPaymentIpnInterface::setOutstandingBalance()
     */
    public function setOutstandingBalance(array $outstandingBalance)
    {
        return $this->setData(self::OUTSTANDING_BALANCE, $outstandingBalance);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\DeferredPaymentIpnInterface::setPayments()
     */
    public function setPayments($payments)
    {
        return $this->setData(self::PAYMENTS, $payments);
    }
}
