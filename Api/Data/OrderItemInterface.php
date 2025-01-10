<?php

/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Api\Data;

/**
 * Interface Hokodo\BNPL\Api\Data\OrderItemInterface.
 */
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
    public const TOTAL_AMOUNT = 'total_amount';
    public const TAX_AMOUNT = 'tax_amount';
    public const TAX_RATE = 'tax_rate';
    public const FULFILLED_QUANTITY = 'fulfilled_quantity';
    public const FULFILLMENT_INFO = 'fulfillment_info';
    public const CANCELLED_QUANTITY = 'cancelled_quantity';
    public const CANCELLED_INFO = 'cancelled_info';
    public const RETURNED_QUANTITY = 'returned_quantity';
    public const RETURNED_INFO = 'returned_info';

    /**
     * A function that sets item id.
     *
     * @param string $itemId
     *
     * @return $this
     */
    public function setItemId($itemId);

    /**
     * A function that gets item id.
     *
     * @return string
     */
    public function getItemId();

    /**
     * A function that sets type.
     *
     * @param string $type
     *
     * @return $this
     */
    public function setType($type);

    /**
     * A function that gets type.
     *
     * @return string
     */
    public function getType();

    /**
     * A function that sets description.
     *
     * @param string $description
     *
     * @return $this
     */
    public function setDescription($description);

    /**
     * A function that gets description.
     *
     * @return string
     */
    public function getDescription();

    /**
     * A function that sets metadata.
     *
     * @param string[] $metadata
     *
     * @return $this
     */
    public function setMetadata($metadata);

    /**
     * A function that gets metadata.
     *
     * @return string[]
     */
    public function getMetadata();

    /**
     * A function that sets reference.
     *
     * @param string $reference
     *
     * @return $this
     */
    public function setReference($reference);

    /**
     * A function that gets reference.
     *
     * @return string
     */
    public function getReference();

    /**
     * A function that sets category.
     *
     * @param string $category
     *
     * @return $this
     */
    public function setCategory($category);

    /**
     * A function that gets category.
     *
     * @return string
     */
    public function getCategory();

    /**
     * A function that sets supplier id.
     *
     * @param string $supplierId
     *
     * @return $this
     */
    public function setSupplierId($supplierId);

    /**
     * A function that gets supplier id.
     *
     * @return string
     */
    public function getSupplierId();

    /**
     * A function that sets suppler name.
     *
     * @param string $supplierName
     *
     * @return $this
     */
    public function setSupplierName($supplierName);

    /**
     * A function that gets supplier name.
     *
     * @return string
     */
    public function getSupplierName();

    /**
     * A function that sets quantity.
     *
     * @param string $quantity
     *
     * @return $this
     */
    public function setQuantity($quantity);

    /**
     * A function that gets quantity.
     *
     * @return string
     */
    public function getQuantity();

    /**
     * A function that sets unit price.
     *
     * @param string $unitPrice
     *
     * @return $this
     */
    public function setUnitPrice($unitPrice);

    /**
     * A function that gets unit price.
     *
     * @return string
     */
    public function getUnitPrice();

    /**
     *  A function that sets total amount.
     *
     * @param string $totalAmount
     *
     * @return $this
     */
    public function setTotalAmount($totalAmount);

    /**
     * A function that gets total amount.
     *
     * @return string
     */
    public function getTotalAmount();

    /**
     * A function that sets tax amount.
     *
     * @param string $taxAmount
     *
     * @return $this
     */
    public function setTaxAmount($taxAmount);

    /**
     * A function that gets tax amount.
     *
     * @return string
     */
    public function getTaxAmount();

    /**
     * A function that sets tax rate.
     *
     * @param string $taxRate
     *
     * @return $this
     */
    public function setTaxRate($taxRate);

    /**
     * A function that gets tax rate.
     *
     * @return string
     */
    public function getTaxRate();

    /**
     * A function that sets fulfillment quantity.
     *
     * @param string $fulfilledQuantity
     *
     * @return $this
     */
    public function setFulfilledQuantity($fulfilledQuantity);

    /**
     * A function that gets fulfillment quantity.
     *
     * @return string
     */
    public function getFulfilledQuantity();

    /**
     * A function that sets fulfillment info.
     *
     * @param FulfillmentInfoInterface[] $fulfillmentInfo
     *
     * @return $this
     */
    public function setFulfillmentInfo(array $fulfillmentInfo);

    /**
     * A function that gets fulfillment info.
     *
     * @return FulfillmentInfoInterface[]
     */
    public function getFulfillmentInfo();

    /**
     * A function that sets cancelled quantity.
     *
     * @param string $cancelledQuantity
     *
     * @return $this
     */
    public function setCancelledQuantity($cancelledQuantity);

    /**
     * A function that gets cancelled quantity.
     *
     * @return string
     */
    public function getCancelledQuantity();

    /**
     * A function that sets cancelled info.
     *
     * @param FulfillmentInfoInterface[] $cancelledInfo
     *
     * @return $this
     */
    public function setCancelledInfo(array $cancelledInfo);

    /**
     * A function that gets cancelled info.
     *
     * @return FulfillmentInfoInterface[]
     */
    public function getCancelledInfo();

    /**
     * A function that sets returned quantity.
     *
     * @param string $returnedQuantity
     *
     * @return $this
     */
    public function setReturnedQuantity($returnedQuantity);

    /**
     * A function that gets returned quantity.
     *
     * @return string
     */
    public function getReturnedQuantity();

    /**
     * A function that sets returned information.
     *
     * @param FulfillmentInfoInterface[] $returnedInfo
     *
     * @return $this
     */
    public function setReturnedInfo(array $returnedInfo);

    /**
     * A function that gets returned info.
     *
     * @return FulfillmentInfoInterface[]
     */
    public function getReturnedInfo();
}
