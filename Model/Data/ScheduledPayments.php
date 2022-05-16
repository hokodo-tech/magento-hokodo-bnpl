<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Model\Data;

use Hokodo\BNPL\Api\Data\ScheduledPaymentsInterface;
use Magento\Framework\Api\AbstractSimpleObject;

/**
 * Class Hokodo\BNPL\Model\Data\ScheduledPayments.
 */
class ScheduledPayments extends AbstractSimpleObject implements ScheduledPaymentsInterface
{
    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\ScheduledPaymentsInterface::getAllowedPaymentMethods()
     */
    public function getAllowedPaymentMethods()
    {
        return $this->_get(self::ALLOWED_PAYMENT_METHODS);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\ScheduledPaymentsInterface::getAmount()
     */
    public function getAmount()
    {
        return $this->_get(self::AMOUNT);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\ScheduledPaymentsInterface::getDate()
     */
    public function getDate()
    {
        return $this->_get(self::DATE);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\ScheduledPaymentsInterface::setAllowedPaymentMethods()
     */
    public function setAllowedPaymentMethods(array $allowedPaymentMethods)
    {
        return $this->setData(self::ALLOWED_PAYMENT_METHODS, $allowedPaymentMethods);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\ScheduledPaymentsInterface::setAmount()
     */
    public function setAmount($amount)
    {
        return $this->setData(self::AMOUNT, $amount);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\ScheduledPaymentsInterface::setDate()
     */
    public function setDate($date)
    {
        return $this->setData(self::DATE, $date);
    }
}
