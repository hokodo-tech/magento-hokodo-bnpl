<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Model\Data;

use Hokodo\BNPL\Api\Data\OrderCustomerIpnInterface;
use Magento\Framework\Api\AbstractSimpleObject;

/**
 * Class Hokodo\BNPL\Model\Data\OrderCustomerIpn.
 */
class OrderCustomerIpn extends AbstractSimpleObject implements OrderCustomerIpnInterface
{
    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderCustomerIpnInterface::setType()
     */
    public function setType($type)
    {
        return $this->setData(self::TYPE, $type);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderCustomerIpnInterface::getType()
     */
    public function getType()
    {
        return $this->_get(self::TYPE);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderCustomerIpnInterface::setOrganisation()
     */
    public function setOrganisation(\Hokodo\BNPL\Api\Data\OrganisationIpnInterface $organisation)
    {
        return $this->setData(self::ORGANISATION, $organisation);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderCustomerIpnInterface::getOrganisation()
     */
    public function getOrganisation()
    {
        return $this->_get(self::ORGANISATION);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderCustomerIpnInterface::setUser()
     */
    public function setUser(\Hokodo\BNPL\Api\Data\UserInterface $user)
    {
        return $this->setData(self::USER, $user);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderCustomerIpnInterface::getUser()
     */
    public function getUser()
    {
        return $this->_get(self::USER);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderCustomerIpnInterface::setDeliveryAddress()
     */
    public function setDeliveryAddress(\Hokodo\BNPL\Api\Data\OrderCustomerAddressInterface $deliveryAddress)
    {
        return $this->setData(self::DELIVERY_ADDRESS, $deliveryAddress);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderCustomerIpnInterface::getDeliveryAddress()
     */
    public function getDeliveryAddress()
    {
        return $this->_get(self::DELIVERY_ADDRESS);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderCustomerIpnInterface::setInvoiceAddress()
     */
    public function setInvoiceAddress(\Hokodo\BNPL\Api\Data\OrderCustomerAddressInterface $invoiceAddress)
    {
        return $this->setData(self::INVOICE_ADDRESS, $invoiceAddress);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderCustomerIpnInterface::getInvoiceAddress()
     */
    public function getInvoiceAddress()
    {
        return $this->_get(self::INVOICE_ADDRESS);
    }

    /**
     * A function that sets billing address.
     *
     * @param \Hokodo\BNPL\Api\Data\OrderCustomerAddressInterface $billingAddress
     *
     * @return $this
     */
    public function setBillingAddress(
        \Hokodo\BNPL\Api\Data\OrderCustomerAddressInterface $billingAddress = null
    ) {
        return $this->setData(self::BILLING_ADDRESS, $billingAddress);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderCustomerIpnInterface::getBillingAddress()
     */
    public function getBillingAddress()
    {
        return $this->_get(self::BILLING_ADDRESS);
    }
}
