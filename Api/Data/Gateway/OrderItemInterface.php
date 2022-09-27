<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Hokodo\BNPL\Api\Data\Gateway;

interface OrderItemInterface
{
    public const ITEM_ID = 'item_id';
    public const TYPE = 'type';
    public const DESCRIPTION = 'description';
    public const METADATA = 'metadata';
    public const REFERENCE = 'reference';
    public const CATEGORY = 'category';
    public const SUPPLIER_ID = 'supplier_id';
    public const SUPPLIER_NAME = 'supplier_name';
    public const QUANTITY = 'quantity';
    public const UNIT_PRICE = 'unit_price';
    public const TAX_RATE = 'tax_rate';
    public const TOTAL_AMOUNT = 'total_amount';
    public const TAX_AMOUNT = 'tax_amount';
    public const FULFILLED_QUANTITY = 'fulfilled_quantity';
    public const FULFILLMENT_INFO = 'fulfillment_info';
    public const CANCELLED_QUANTITY = 'cancelled_quantity';
    public const CANCELLED_INFO = 'cancelled_info';
    public const RETURNED_QUANTITY = 'returned_quantity';
    public const RETURNED_INFO = 'returned_info';

    /**
     * Item Id setter.
     *
     * @param string $itemId
     *
     * @return $this
     */
    public function setItemId(string $itemId): self;

    /**
     * Type setter.
     *
     * @param string $type
     *
     * @return $this
     */
    public function setType(string $type): self;

    /**
     * Description setter.
     *
     * @param string $description
     *
     * @return $this
     */
    public function setDescription(string $description): self;

    /**
     * Metadata setter.
     *
     * @param array $metadata
     *
     * @return $this
     */
    public function setMetadata(array $metadata): self;

    /**
     * Reference setter.
     *
     * @param string $reference
     *
     * @return $this
     */
    public function setReference(string $reference): self;

    /**
     * Category setter.
     *
     * @param string $category
     *
     * @return $this
     */
    public function setCategory(string $category): self;

    /**
     * Supplier Id setter.
     *
     * @param string $supplierId
     *
     * @return $this
     */
    public function setSupplierId(string $supplierId): self;

    /**
     * Supplier Name setter.
     *
     * @param string $supplierName
     *
     * @return $this
     */
    public function setSupplierName(string $supplierName): self;

    /**
     * Quantity setter.
     *
     * @param string $quantity
     *
     * @return $this
     */
    public function setQuantity(string $quantity): self;

    /**
     * Unit Price setter.
     *
     * @param int $unitPrice
     *
     * @return $this
     */
    public function setUnitPrice(int $unitPrice): self;

    /**
     * Tax Rate setter.
     *
     * @param string $taxRate
     *
     * @return $this
     */
    public function setTaxRate(string $taxRate): self;

    /**
     * Total Amount setter.
     *
     * @param int $totalAmount
     *
     * @return $this
     */
    public function setTotalAmount(int $totalAmount): self;

    /**
     * Tax Amount setter.
     *
     * @param int $taxAmount
     *
     * @return $this
     */
    public function setTaxAmount(int $taxAmount): self;

    /**
     * Fulfilled Quantity setter.
     *
     * @param int $fulfilledQuantity
     *
     * @return $this
     */
    public function setFulfilledQuantity(int $fulfilledQuantity): self;

    /**
     * Fulfillment Info setter.
     *
     * @param mixed $fulfillmentInfo
     *
     * @return $this
     */
    public function setFulfillmentInfo($fulfillmentInfo): self;

    /**
     * Cancelled Quantity setter.
     *
     * @param int $cancelledQuantity
     *
     * @return $this
     */
    public function setCancelledQuantity(int $cancelledQuantity): self;

    /**
     * Cancelled Info setter.
     *
     * @param mixed $cancelledInfo
     *
     * @return $this
     */
    public function setCancelledInfo($cancelledInfo): self;

    /**
     * Returned Quantity setter.
     *
     * @param int $returnedQuantity
     *
     * @return $this
     */
    public function setReturnedQuantity(int $returnedQuantity): self;

    /**
     * Returned Info setter.
     *
     * @param mixed $returnedInfo
     *
     * @return $this
     */
    public function setReturnedInfo($returnedInfo): self;
}
