<?php
/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Api\Data;

/**
 * Interface Hokodo\BNPL\Api\Data\OrderCustomerInterface.
 */
interface OrderCustomerIpnInterface extends \Magento\Framework\Api\ExtensibleDataInterface
{
    public const ORGANISATION = 'organisation';
    public const USER = 'user';
    public const INVOICE_ADDRESS = 'invoice_address';
    public const DELIVERY_ADDRESS = 'delivery_address';
    public const BILLING_ADDRESS = 'billing_address';

    /**
     * A function that sets organisation.
     *
     * @param \Hokodo\BNPL\Api\Data\OrganisationIpnInterface $organisation
     *
     * @return $this
     */
    public function setOrganisation(OrganisationIpnInterface $organisation);

    /**
     * A function that gets organisation.
     *
     * @return \Hokodo\BNPL\Api\Data\OrganisationIpnInterface
     */
    public function getOrganisation();

    /**
     * A function that sets user.
     *
     * @param \Hokodo\BNPL\Api\Data\UserInterface $user
     *
     * @return $this
     */
    public function setUser(UserInterface $user);

    /**
     * A function that gets user.
     *
     * @return \Hokodo\BNPL\Api\Data\UserInterface
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

    /**
     * A function that sets billing address.
     *
     * @param \Hokodo\BNPL\Api\Data\OrderCustomerAddressInterface $billingAddress
     *
     * @return $this
     */
    public function setBillingAddress(
        OrderCustomerAddressInterface $billingAddress = null
    );

    /**
     * A function that gets billing address.
     *
     * @return \Hokodo\BNPL\Api\Data\OrderCustomerAddressInterface|null
     */
    public function getBillingAddress();
}
