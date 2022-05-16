<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Model\Data;

use Hokodo\BNPL\Api\Data\OrderIpnInterface;
use Magento\Framework\Api\AbstractSimpleObject;

/**
 * Class Hokodo\BNPL\Model\Data\OrderIpn.
 */
class OrderIpn extends AbstractSimpleObject implements OrderIpnInterface
{
    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderIpnInterface::setId()
     */
    public function setId($id)
    {
        return $this->setData(self::ID, $id);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderIpnInterface::getId()
     */
    public function getId()
    {
        return $this->_get(self::ID);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderIpnInterface::setUniqueId()
     */
    public function setUniqueId($uniqueId)
    {
        return $this->setData(self::UNIQUE_ID, $uniqueId);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderIpnInterface::getUniqueId()
     */
    public function getUniqueId()
    {
        return $this->_get(self::UNIQUE_ID);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderIpnInterface::setPoNumber()
     */
    public function setPoNumber($poNumber)
    {
        return $this->setData(self::PO_NUMBER, $poNumber);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderIpnInterface::getPoNumber()
     */
    public function getPoNumber()
    {
        return $this->_get(self::PO_NUMBER);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderIpnInterface::setCustomer()
     */
    public function setCustomer(\Hokodo\BNPL\Api\Data\OrderCustomerIpnInterface $customer)
    {
        return $this->setData(self::CUSTOMER, $customer);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderIpnInterface::getCustomer()
     */
    public function getCustomer()
    {
        return $this->_get(self::CUSTOMER);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderIpnInterface::setCreated()
     */
    public function setCreated($created)
    {
        return $this->setData(self::CREATED, $created);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderIpnInterface::getCreated()
     */
    public function getCreated()
    {
        return $this->_get(self::CREATED);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderIpnInterface::setCurrency()
     */
    public function setCurrency($currency)
    {
        return $this->setData(self::CURRENCY, $currency);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderIpnInterface::getCurrency()
     */
    public function getCurrency()
    {
        return $this->_get(self::CURRENCY);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderIpnInterface::setOrderDate()
     */
    public function setOrderDate($orderDate)
    {
        return $this->setData(self::ORDER_DATE, $orderDate);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderIpnInterface::getOrderDate()
     */
    public function getOrderDate()
    {
        return $this->_get(self::ORDER_DATE);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderIpnInterface::setInvoiceDate()
     */
    public function setInvoiceDate($invoiceDate)
    {
        return $this->setData(self::INVOICE_DATE, $invoiceDate);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderIpnInterface::getInvoiceDate()
     */
    public function getInvoiceDate()
    {
        return $this->_get(self::INVOICE_DATE);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderIpnInterface::setDueDate()
     */
    public function setDueDate($dueDate)
    {
        return $this->setData(self::DUE_DATE, $dueDate);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderIpnInterface::getDueDate()
     */
    public function getDueDate()
    {
        return $this->_get(self::DUE_DATE);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderIpnInterface::setPaidDate()
     */
    public function setPaidDate($paidDate)
    {
        return $this->setData(self::PAID_DATE, $paidDate);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderIpnInterface::getPaidDate()
     */
    public function getPaidDate()
    {
        return $this->_get(self::PAID_DATE);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderIpnInterface::setTotalAmount()
     */
    public function setTotalAmount($totalAmount)
    {
        return $this->setData(self::TOTAL_AMOUNT, $totalAmount);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderIpnInterface::getTotalAmount()
     */
    public function getTotalAmount()
    {
        return $this->_get(self::TOTAL_AMOUNT);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderIpnInterface::setTaxAmount()
     */
    public function setTaxAmount($taxAmount)
    {
        return $this->setData(self::TAX_AMOUNT, $taxAmount);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderIpnInterface::getTaxAmount()
     */
    public function getTaxAmount()
    {
        return $this->_get(self::TAX_AMOUNT);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderIpnInterface::setMetadata()
     */
    public function setMetadata($metadata)
    {
        return $this->setData(self::METADATA, $metadata);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderIpnInterface::getMetadata()
     */
    public function getMetadata()
    {
        return $this->_get(self::METADATA);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderIpnInterface::setItems()
     */
    public function setItems(array $items)
    {
        return $this->setData(self::ITEMS, $items);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderIpnInterface::getItems()
     */
    public function getItems()
    {
        return $this->_get(self::ITEMS);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderIpnInterface::setPaymentOffer()
     */
    public function setPaymentOffer(\Hokodo\BNPL\Api\Data\PaymentOffersInterface $paymentOffer)
    {
        return $this->setData(self::PAYMENT_OFFER, $paymentOffer);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderIpnInterface::getPaymentOffer()
     */
    public function getPaymentOffer()
    {
        return $this->_get(self::PAYMENT_OFFER);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderIpnInterface::setStatus()
     */
    public function setStatus($status)
    {
        return $this->setData(self::STATUS, $status);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderIpnInterface::getStatus()
     */
    public function getStatus()
    {
        return $this->_get(self::STATUS);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderIpnInterface::setPayMethod()
     */
    public function setPayMethod($payMethod)
    {
        return $this->setData(self::PAY_METHOD, $payMethod);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderIpnInterface::getPayMethod()
     */
    public function getPayMethod()
    {
        return $this->_get(self::PAY_METHOD);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderIpnInterface::setDeferredPayment()
     */
    public function setDeferredPayment(\Hokodo\BNPL\Api\Data\DeferredPaymentIpnInterface $deferredPayment)
    {
        return $this->setData(self::DEFERRED_PAYMENT, $deferredPayment);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderIpnInterface::getDeferredPayment()
     */
    public function getDeferredPayment()
    {
        return $this->_get(self::DEFERRED_PAYMENT);
    }
}
