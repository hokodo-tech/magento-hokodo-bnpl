<?php
/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Model\Data;

use Hokodo\BNPL\Api\Data\FulfillmentInfoInterface;
use Magento\Framework\Api\AbstractSimpleObject;

/**
 * Class Hokodo\BNPL\Model\Data\FulfillmentInfo.
 */
class FulfillmentInfo extends AbstractSimpleObject implements FulfillmentInfoInterface
{
    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\FulfillmentInfoInterface::setQuantity()
     */
    public function setQuantity($quantity)
    {
        return $this->setData(self::QUANTITY, $quantity);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\FulfillmentInfoInterface::getQuantity()
     */
    public function getQuantity()
    {
        return $this->_get(self::QUANTITY);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\FulfillmentInfoInterface::setTotalAmount()
     */
    public function setTotalAmount($totalAmount)
    {
        return $this->setData(self::TOTAL_AMOUNT, $totalAmount);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\FulfillmentInfoInterface::getTotalAmount()
     */
    public function getTotalAmount()
    {
        return $this->_get(self::TOTAL_AMOUNT);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\FulfillmentInfoInterface::setTaxAmount()
     */
    public function setTaxAmount($taxAmount)
    {
        return $this->setData(self::TAX_AMOUNT, $taxAmount);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\FulfillmentInfoInterface::getTaxAmount()
     */
    public function getTaxAmount()
    {
        return $this->_get(self::TAX_AMOUNT);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\FulfillmentInfoInterface::getShippingId()
     */
    public function getShippingId()
    {
        return $this->_get(self::SHIPPING_ID);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\FulfillmentInfoInterface::getShippingProvider()
     */
    public function getShippingProvider()
    {
        return $this->_get(self::SHIPPING_PROVIDER);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\FulfillmentInfoInterface::setShippingId()
     */
    public function setShippingId($shippingId)
    {
        return $this->setData(self::SHIPPING_ID, $shippingId);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\FulfillmentInfoInterface::setShippingProvider()
     */
    public function setShippingProvider($shippingProvider)
    {
        return $this->setData(self::SHIPPING_PROVIDER, $shippingProvider);
    }
}
