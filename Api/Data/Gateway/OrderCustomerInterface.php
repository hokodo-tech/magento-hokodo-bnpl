<?php

/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
// @codingStandardsIgnoreFile

declare(strict_types=1);

namespace Hokodo\BNPL\Api\Data\Gateway;

interface OrderCustomerInterface
{
    public const TYPE = 'type';
    public const ORGANISATION = 'organisation';
    public const USER = 'user';
    public const DELIVERY_ADDRESS = 'delivery_address';
    public const INVOICE_ADDRESS = 'invoice_address';

    /**
     * Type setter.
     *
     * @param string $type
     *
     * @return $this
     */
    public function setType(string $type): self;

    /**
     * Organisation setter.
     *
     * @param string $organisation
     *
     * @return $this
     */
    public function setOrganisation(string $organisation): self;

    /**
     * User setter.
     *
     * @param string $user
     *
     * @return $this
     */
    public function setUser(string $user): self;

    /**
     * Delivery Address setter.
     *
     * @param \Hokodo\BNPL\Api\Data\Gateway\CustomerAddressInterface $deliveryAddress
     *
     * @return $this
     */
    public function setDeliveryAddress(CustomerAddressInterface $deliveryAddress): self;

    /**
     * Invoice Address setter.
     *
     * @param \Hokodo\BNPL\Api\Data\Gateway\CustomerAddressInterface $invoiceAddress
     *
     * @return $this
     */
    public function setInvoiceAddress(CustomerAddressInterface $invoiceAddress): self;
}
