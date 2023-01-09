<?php
/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Api\Data;

/**
 * Interface Hokodo\BNPL\Api\Data\FulfillmentInfoInterface.
 */
interface FulfillmentInfoInterface
{
    public const QUANTITY = 'quantity';
    public const TOTAL_AMOUNT = 'total_amount';
    public const TAX_AMOUNT = 'tax_amount';
    public const SHIPPING_ID = 'shipping_id';
    public const SHIPPING_PROVIDER = 'shipping_provider';

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
     * A function that sets total amount.
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
     * A function that sets shipping id.
     *
     * @param string $shippingId
     *
     * @return $this
     */
    public function setShippingId($shippingId);

    /**
     * A function that gets shipping id.
     *
     * @return string
     */
    public function getShippingId();

    /**
     * A function that sets shipping provider.
     *
     * @param string $shippingProvider
     *
     * @return $this
     */
    public function setShippingProvider($shippingProvider);

    /**
     * A function that gets shipping provider.
     *
     * @return string
     */
    public function getShippingProvider();
}
