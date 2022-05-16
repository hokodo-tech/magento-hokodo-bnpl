<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Model\Data;

use Hokodo\BNPL\Api\Data\PaymentPlanInterface;
use Magento\Framework\Api\AbstractSimpleObject;

/**
 * Class Hokodo\BNPL\Model\Data\PaymentPlan.
 */
class PaymentPlan extends AbstractSimpleObject implements PaymentPlanInterface
{
    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\PaymentPlanInterface::getId()
     */
    public function getId()
    {
        return $this->_get(self::ID);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\PaymentPlanInterface::getName()
     */
    public function getName()
    {
        return $this->_get(self::NAME);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\PaymentPlanInterface::getPaymentUrl()
     */
    public function getPaymentUrl()
    {
        return $this->_get(self::PAYMENT_URL);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\PaymentPlanInterface::setId()
     */
    public function setId($id)
    {
        return $this->setData(self::ID, $id);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\PaymentPlanInterface::setName()
     */
    public function setName($name)
    {
        return $this->setData(self::NAME, (string) __($name));
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\PaymentPlanInterface::setPaymentUrl()
     */
    public function setPaymentUrl($paymentUrl)
    {
        return $this->setData(self::PAYMENT_URL, $paymentUrl);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\PaymentPlanInterface::setStatus()
     */
    public function setStatus($status)
    {
        return $this->setData(self::STATUS, $status);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\PaymentPlanInterface::getStatus()
     */
    public function getStatus()
    {
        return $this->_get(self::STATUS);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\PaymentPlanInterface::setRejectionReason()
     */
    public function setRejectionReason(
        \Hokodo\BNPL\Api\Data\RejectionReasonInterface $rejectionReason = null
    ) {
        return $this->setData(self::REJECTION_REASON, $rejectionReason);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\PaymentPlanInterface::getRejectionReason()
     */
    public function getRejectionReason()
    {
        return $this->_get(self::REJECTION_REASON);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\PaymentPlanInterface::getCurrency()
     */
    public function getCurrency()
    {
        return $this->_get(self::CURRENCY);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\PaymentPlanInterface::getCustomerFee()
     */
    public function getCustomerFee()
    {
        return $this->_get(self::CUSTOMER_FEE);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\PaymentPlanInterface::getMerchantFee()
     */
    public function getMerchantFee()
    {
        return $this->_get(self::MERCHANT_FEE);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\PaymentPlanInterface::getScheduledPayments()
     */
    public function getScheduledPayments()
    {
        return $this->_get(self::SCHEDULED_PAYMENTS);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\PaymentPlanInterface::getTemplate()
     */
    public function getTemplate()
    {
        return $this->_get(self::TEMPLATE);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\PaymentPlanInterface::getValidUntil()
     */
    public function getValidUntil()
    {
        return $this->_get(self::VALID_UNTIL);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\PaymentPlanInterface::setCurrency()
     */
    public function setCurrency($currency)
    {
        return $this->setData(self::CURRENCY, $currency);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\PaymentPlanInterface::setCustomerFee()
     */
    public function setCustomerFee(array $customerFee)
    {
        return $this->setData(self::CUSTOMER_FEE, $customerFee);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\PaymentPlanInterface::setMerchantFee()
     */
    public function setMerchantFee(array $merchantFee)
    {
        return $this->setData(self::MERCHANT_FEE, $merchantFee);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\PaymentPlanInterface::setScheduledPayments()
     */
    public function setScheduledPayments(array $scheduledPayments)
    {
        return $this->setData(self::SCHEDULED_PAYMENTS, $scheduledPayments);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\PaymentPlanInterface::setTemplate()
     */
    public function setTemplate($template)
    {
        return $this->setData(self::TEMPLATE, $template);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\PaymentPlanInterface::setValidUntil()
     */
    public function setValidUntil($validUntil)
    {
        return $this->setData(self::VALID_UNTIL, $validUntil);
    }
}
