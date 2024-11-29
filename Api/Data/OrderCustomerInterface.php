<?php

/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
// @codingStandardsIgnoreFile

namespace Hokodo\BNPL\Api\Data;

/**
 * Interface Hokodo\BNPL\Api\Data\OrderCustomerInterface.
 */
interface OrderCustomerInterface extends \Magento\Framework\Api\ExtensibleDataInterface
{
    public const TYPE = 'type';
    public const ORGANISATION = 'organisation';
    public const USER = 'user';
    public const DELIVERY_ADDRESS = 'delivery_address';
    public const INVOICE_ADDRESS = 'invoice_address';

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
     * A function that sets organisation.
     *
     * @param string $organisation
     *
     * @return $this
     */
    public function setOrganisation($organisation);

    /**
     * A function that get organisation.
     *
     * @return string
     */
    public function getOrganisation();

    /**
     * A function that sets user.
     *
     * @param string $user
     *
     * @return $this
     */
    public function setUser($user);

    /**
     * A function that gets user.
     *
     * @return string
     */
    public function getUser();

    /**
     * A function that sets delivery address.
     *
     * @param \Hokodo\BNPL\Api\Data\OrderCustomerAddressInterface $deliveryAddress
     *
     * @return $this
     */
    public function setDeliveryAddress(OrderCustomerAddressInterface $deliveryAddress);

    /**
     * A function that gets delivery address.
     *
     * @return \Hokodo\BNPL\Api\Data\OrderCustomerAddressInterface
     */
    public function getDeliveryAddress();

    /**
     * A function that sets invoice address.
     *
     * @param \Hokodo\BNPL\Api\Data\OrderCustomerAddressInterface $invoiceAddress
     *
     * @return $this
     */
    public function setInvoiceAddress(OrderCustomerAddressInterface $invoiceAddress);

    /**
     * A function that gets invoice address.
     *
     * @return \Hokodo\BNPL\Api\Data\OrderCustomerAddressInterface
     */
    public function getInvoiceAddress();
}
