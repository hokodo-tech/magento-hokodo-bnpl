<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Model\Data;

use Hokodo\BNPL\Api\Data\OrderItemInterface;
use Magento\Framework\Api\AbstractSimpleObject;

/**
 * Class Hokodo\BNPL\Model\Data\OrderItem.
 */
class OrderItem extends AbstractSimpleObject implements OrderItemInterface
{
    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderItemInterface::setItemId()
     */
    public function setItemId($itemId)
    {
        return $this->setData(self::ITEM_ID, $itemId);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderItemInterface::getItemId()
     */
    public function getItemId()
    {
        return $this->_get(self::ITEM_ID);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderItemInterface::setType()
     */
    public function setType($type)
    {
        return $this->setData(self::TYPE, $type);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderItemInterface::getType()
     */
    public function getType()
    {
        return $this->_get(self::TYPE);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderItemInterface::setDescription()
     */
    public function setDescription($description)
    {
        return $this->setData(self::DESCRIPTION, $description);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderItemInterface::getDescription()
     */
    public function getDescription()
    {
        return $this->_get(self::DESCRIPTION);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderItemInterface::setMetadata()
     */
    public function setMetadata($metadata)
    {
        return $this->setData(self::METADATA, $metadata);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderItemInterface::getMetadata()
     */
    public function getMetadata()
    {
        return $this->_get(self::METADATA);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderItemInterface::setReference()
     */
    public function setReference($reference)
    {
        return $this->setData(self::REFERENCE, $reference);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderItemInterface::getReference()
     */
    public function getReference()
    {
        return $this->_get(self::REFERENCE);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderItemInterface::setCategory()
     */
    public function setCategory($category)
    {
        return $this->setData(self::CATEGORY, $category);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderItemInterface::getCategory()
     */
    public function getCategory()
    {
        return $this->_get(self::CATEGORY);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderItemInterface::setSupplierId()
     */
    public function setSupplierId($supplierId)
    {
        return $this->setData(self::SUPPLIER_ID, $supplierId);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderItemInterface::getSupplierId()
     */
    public function getSupplierId()
    {
        return $this->_get(self::SUPPLIER_ID);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderItemInterface::setSupplierName()
     */
    public function setSupplierName($supplierName)
    {
        return $this->setData(self::SUPPLIER_NAME, $supplierName);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderItemInterface::getSupplierName()
     */
    public function getSupplierName()
    {
        return $this->_get(self::SUPPLIER_NAME);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderItemInterface::setQuantity()
     */
    public function setQuantity($quantity)
    {
        return $this->setData(self::QUANTITY, $quantity);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderItemInterface::getQuantity()
     */
    public function getQuantity()
    {
        return $this->_get(self::QUANTITY);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderItemInterface::setUnitPrice()
     */
    public function setUnitPrice($unitPrice)
    {
        return $this->setData(self::UNIT_PRICE, $unitPrice);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderItemInterface::getUnitPrice()
     */
    public function getUnitPrice()
    {
        return $this->_get(self::UNIT_PRICE);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderItemInterface::setTotalAmount()
     */
    public function setTotalAmount($totalAmount)
    {
        return $this->setData(self::TOTAL_AMOUNT, $totalAmount);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderItemInterface::getTotalAmount()
     */
    public function getTotalAmount()
    {
        return $this->_get(self::TOTAL_AMOUNT);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderItemInterface::setTaxAmount()
     */
    public function setTaxAmount($taxAmount)
    {
        return $this->setData(self::TAX_AMOUNT, $taxAmount);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderItemInterface::getTaxAmount()
     */
    public function getTaxAmount()
    {
        return $this->_get(self::TAX_AMOUNT);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderItemInterface::setTaxRate()
     */
    public function setTaxRate($taxRate)
    {
        return $this->setData(self::TAX_RATE, $taxRate);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderItemInterface::getTaxRate()
     */
    public function getTaxRate()
    {
        return $this->_get(self::TAX_RATE);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderItemInterface::setFulfilledQuantity()
     */
    public function setFulfilledQuantity($fulfilledQuantity)
    {
        return $this->setData(self::FULFILLED_QUANTITY, $fulfilledQuantity);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderItemInterface::getFulfilledQuantity()
     */
    public function getFulfilledQuantity()
    {
        return $this->_get(self::FULFILLED_QUANTITY);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderItemInterface::setFulfillmentInfo()
     */
    public function setFulfillmentInfo(array $fulfillmentInfo)
    {
        return $this->setData(self::FULFILLMENT_INFO, $fulfillmentInfo);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderItemInterface::getFulfillmentInfo()
     */
    public function getFulfillmentInfo()
    {
        return $this->_get(self::FULFILLMENT_INFO);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderItemInterface::setCancelledQuantity()
     */
    public function setCancelledQuantity($cancelledQuantity)
    {
        return $this->setData(self::CANCELLED_QUANTITY, $cancelledQuantity);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderItemInterface::getCancelledQuantity()
     */
    public function getCancelledQuantity()
    {
        return $this->_get(self::CANCELLED_QUANTITY);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderItemInterface::setCancelledInfo()
     */
    public function setCancelledInfo(array $cancelledInfo)
    {
        return $this->setData(self::CANCELLED_INFO, $cancelledInfo);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderItemInterface::getCancelledInfo()
     */
    public function getCancelledInfo()
    {
        return $this->_get(self::CANCELLED_INFO);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderItemInterface::setReturnedQuantity()
     */
    public function setReturnedQuantity($returnedQuantity)
    {
        return $this->setData(self::RETURNED_QUANTITY, $returnedQuantity);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderItemInterface::getReturnedQuantity()
     */
    public function getReturnedQuantity()
    {
        return $this->_get(self::RETURNED_QUANTITY);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderItemInterface::setReturnedInfo()
     */
    public function setReturnedInfo(array $returnedInfo)
    {
        return $this->setData(self::RETURNED_INFO, $returnedInfo);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderItemInterface::getReturnedInfo()
     */
    public function getReturnedInfo()
    {
        return $this->_get(self::RETURNED_INFO);
    }
}
