<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Hokodo\BNPL\Model\Data\Gateway;

use Hokodo\BNPL\Api\Data\Gateway\OrderItemInterface;
use Magento\Framework\Api\AbstractSimpleObject;

class OrderItem extends AbstractSimpleObject implements OrderItemInterface
{
    /**
     * @inheritdoc
     */
    public function setItemId(string $itemId): self
    {
        $this->setData(self::ITEM_ID, $itemId);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setType(string $type): self
    {
        $this->setData(self::TYPE, $type);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setDescription(string $description): self
    {
        $this->setData(self::DESCRIPTION, $description);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setMetadata(array $metadata): self
    {
        $this->setData(self::METADATA, $metadata);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setReference(string $reference): self
    {
        $this->setData(self::REFERENCE, $reference);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setCategory(string $category): self
    {
        $this->setData(self::CATEGORY, $category);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setSupplierId(string $supplierId): self
    {
        $this->setData(self::SUPPLIER_ID, $supplierId);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setSupplierName(string $supplierName): self
    {
        $this->setData(self::SUPPLIER_NAME, $supplierName);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setQuantity(string $quantity): self
    {
        $this->setData(self::QUANTITY, $quantity);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setUnitPrice(int $unitPrice): self
    {
        $this->setData(self::UNIT_PRICE, $unitPrice);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setTaxRate(string $taxRate): self
    {
        $this->setData(self::TAX_RATE, $taxRate);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setTotalAmount(int $totalAmount): self
    {
        $this->setData(self::TOTAL_AMOUNT, $totalAmount);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setTaxAmount(int $taxAmount): self
    {
        $this->setData(self::TAX_AMOUNT, $taxAmount);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setFulfilledQuantity(int $fulfilledQuantity = 0): self
    {
        $this->setData(self::FULFILLED_QUANTITY, $fulfilledQuantity);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setFulfillmentInfo($fulfillmentInfo = null): self
    {
        $this->setData(self::FULFILLMENT_INFO, $fulfillmentInfo);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setCancelledQuantity(int $cancelledQuantity = 0): self
    {
        $this->setData(self::CANCELLED_QUANTITY, $cancelledQuantity);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setCancelledInfo($cancelledInfo = null): self
    {
        $this->setData(self::CANCELLED_INFO, $cancelledInfo);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setReturnedQuantity(int $returnedQuantity = 0): self
    {
        $this->setData(self::RETURNED_QUANTITY, $returnedQuantity);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setReturnedInfo($returnedInfo = null): self
    {
        $this->setData(self::RETURNED_INFO, $returnedInfo);
        return $this;
    }
}
