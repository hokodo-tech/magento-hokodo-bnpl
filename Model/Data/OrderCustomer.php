<?php
/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Model\Data;

use Hokodo\BNPL\Api\Data\OrderCustomerInterface;
use Magento\Framework\Api\AbstractSimpleObject;

/**
 * Class Hokodo\BNPL\Model\Data\OrderCustomer.
 */
class OrderCustomer extends AbstractSimpleObject implements OrderCustomerInterface
{
    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderCustomerInterface::setType()
     */
    public function setType($type)
    {
        return $this->setData(self::TYPE, $type);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderCustomerInterface::getType()
     */
    public function getType()
    {
        return $this->_get(self::TYPE);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderCustomerInterface::setOrganisation()
     */
    public function setOrganisation($organisation)
    {
        return $this->setData(self::ORGANISATION, $organisation);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderCustomerInterface::getOrganisation()
     */
    public function getOrganisation()
    {
        return $this->_get(self::ORGANISATION);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderCustomerInterface::setUser()
     */
    public function setUser($user)
    {
        return $this->setData(self::USER, $user);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderCustomerInterface::getUser()
     */
    public function getUser()
    {
        return $this->_get(self::USER);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderCustomerInterface::setDeliveryAddress()
     */
    public function setDeliveryAddress(\Hokodo\BNPL\Api\Data\OrderCustomerAddressInterface $deliveryAddress)
    {
        return $this->setData(self::DELIVERY_ADDRESS, $deliveryAddress);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderCustomerInterface::getDeliveryAddress()
     */
    public function getDeliveryAddress()
    {
        return $this->_get(self::DELIVERY_ADDRESS);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderCustomerInterface::setInvoiceAddress()
     */
    public function setInvoiceAddress(\Hokodo\BNPL\Api\Data\OrderCustomerAddressInterface $invoiceAddress)
    {
        return $this->setData(self::INVOICE_ADDRESS, $invoiceAddress);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderCustomerInterface::getInvoiceAddress()
     */
    public function getInvoiceAddress()
    {
        return $this->_get(self::INVOICE_ADDRESS);
    }
}
