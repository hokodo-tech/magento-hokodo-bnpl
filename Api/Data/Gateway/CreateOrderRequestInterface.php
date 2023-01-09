<?php
/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
// @codingStandardsIgnoreFile

declare(strict_types=1);

namespace Hokodo\BNPL\Api\Data\Gateway;

interface CreateOrderRequestInterface
{
    public const UNIQUE_ID = 'unique_id';
    public const CUSTOMER = 'customer';
    public const STATUS = 'status';
    public const CURRENCY = 'currency';
    public const TOTAL_AMOUNT = 'total_amount';
    public const TAX_AMOUNT = 'tax_amount';
    public const ORDER_DATE = 'order_date';
    public const ITEMS = 'items';
    public const METADATA = 'metadata';

    /**
     * Unique Id setter.
     *
     * @param string $uniqueId
     *
     * @return $this
     */
    public function setUniqueId(string $uniqueId): self;

    /**
     * Customer setter.
     *
     * @param OrderCustomerInterface $customer
     *
     * @return $this
     */
    public function setCustomer(OrderCustomerInterface $customer): self;

    /**
     * Status setter.
     *
     * @param string $status
     *
     * @return $this
     */
    public function setStatus(string $status): self;

    /**
     * Currency setter.
     *
     * @param string $currency
     *
     * @return $this
     */
    public function setCurrency(string $currency): self;

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
     * Order Date setter.
     * pattern: 2000-01-31.
     *
     * @param string $orderDate
     *
     * @return $this
     */
    public function setOrderDate(string $orderDate): self;

    /**
     * Items setter.
     *
     * @param OrderItemInterface[] $items
     *
     * @return $this
     */
    public function setItems(array $items): self;

    /**
     * Metadata setter.
     *
     * @param array $metadata
     *
     * @return $this
     */
    public function setMetadata(array $metadata): self;
}
