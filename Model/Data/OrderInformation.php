<?php
/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Model\Data;

use Hokodo\BNPL\Api\Data\OrderInformationInterface;
use Hokodo\BNPL\Api\Data\OrderItemInterface;
use Magento\Framework\Api\AbstractSimpleObject;

/**
 * Class Hokodo\BNPL\Model\Data\OrderInformation.
 */
class OrderInformation extends AbstractSimpleObject implements OrderInformationInterface
{
    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderInformationInterface::setId()
     */
    public function setId($id)
    {
        return $this->setData(self::ID, $id);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderInformationInterface::getId()
     */
    public function getId()
    {
        return $this->_get(self::ID);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderInformationInterface::setUniqueId()
     */
    public function setUniqueId($uniqueId)
    {
        return $this->setData(self::UNIQUE_ID, $uniqueId);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderInformationInterface::getUniqueId()
     */
    public function getUniqueId()
    {
        return $this->_get(self::UNIQUE_ID);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderInformationInterface::setPoNumber()
     */
    public function setPoNumber($poNumber)
    {
        return $this->setData(self::PO_NUMBER, $poNumber);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderInformationInterface::getPoNumber()
     */
    public function getPoNumber()
    {
        return $this->_get(self::PO_NUMBER);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderInformationInterface::setCustomer()
     */
    public function setCustomer(\Hokodo\BNPL\Api\Data\OrderCustomerInterface $customer)
    {
        return $this->setData(self::CUSTOMER, $customer);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderInformationInterface::getCustomer()
     */
    public function getCustomer()
    {
        return $this->_get(self::CUSTOMER);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderInformationInterface::setCreated()
     */
    public function setCreated($created)
    {
        return $this->setData(self::CREATED, $created);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderInformationInterface::getCreated()
     */
    public function getCreated()
    {
        return $this->_get(self::CREATED);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderInformationInterface::setCurrency()
     */
    public function setCurrency($currency)
    {
        return $this->setData(self::CURRENCY, $currency);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderInformationInterface::getCurrency()
     */
    public function getCurrency()
    {
        return $this->_get(self::CURRENCY);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderInformationInterface::setOrderDate()
     */
    public function setOrderDate($orderDate)
    {
        return $this->setData(self::ORDER_DATE, $orderDate);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderInformationInterface::getOrderDate()
     */
    public function getOrderDate()
    {
        return $this->_get(self::ORDER_DATE);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderInformationInterface::setInvoiceDate()
     */
    public function setInvoiceDate($invoiceDate)
    {
        return $this->setData(self::INVOICE_DATE, $invoiceDate);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderInformationInterface::getInvoiceDate()
     */
    public function getInvoiceDate()
    {
        return $this->_get(self::INVOICE_DATE);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderInformationInterface::setDueDate()
     */
    public function setDueDate($dueDate)
    {
        return $this->setData(self::DUE_DATE, $dueDate);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderInformationInterface::getDueDate()
     */
    public function getDueDate()
    {
        return $this->_get(self::DUE_DATE);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderInformationInterface::setPaidDate()
     */
    public function setPaidDate($paidDate)
    {
        return $this->setData(self::PAID_DATE, $paidDate);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderInformationInterface::getPaidDate()
     */
    public function getPaidDate()
    {
        return $this->_get(self::PAID_DATE);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderInformationInterface::setTotalAmount()
     */
    public function setTotalAmount($totalAmount)
    {
        return $this->setData(self::TOTAL_AMOUNT, $totalAmount);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderInformationInterface::getTotalAmount()
     */
    public function getTotalAmount()
    {
        return $this->_get(self::TOTAL_AMOUNT);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderInformationInterface::setTaxAmount()
     */
    public function setTaxAmount($taxAmount)
    {
        return $this->setData(self::TAX_AMOUNT, $taxAmount);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderInformationInterface::getTaxAmount()
     */
    public function getTaxAmount()
    {
        return $this->_get(self::TAX_AMOUNT);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderInformationInterface::setMetadata()
     */
    public function setMetadata($metadata)
    {
        return $this->setData(self::METADATA, $metadata);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderInformationInterface::getMetadata()
     */
    public function getMetadata()
    {
        return $this->_get(self::METADATA);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderInformationInterface::setItems()
     */
    public function setItems(array $items)
    {
        return $this->setData(self::ITEMS, $items);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderInformationInterface::getItems()
     */
    public function getItems()
    {
        return $this->_get(self::ITEMS);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderInformationInterface::setPaymentOffer()
     */
    public function setPaymentOffer($paymentOffer)
    {
        return $this->setData(self::PAYMENT_OFFER, $paymentOffer);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderInformationInterface::getPaymentOffer()
     */
    public function getPaymentOffer()
    {
        return $this->_get(self::PAYMENT_OFFER);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderInformationInterface::setStatus()
     */
    public function setStatus($status)
    {
        return $this->setData(self::STATUS, $status);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderInformationInterface::getStatus()
     */
    public function getStatus()
    {
        return $this->_get(self::STATUS);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderInformationInterface::setPayMethod()
     */
    public function setPayMethod($payMethod)
    {
        return $this->setData(self::PAY_METHOD, $payMethod);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderInformationInterface::getPayMethod()
     */
    public function getPayMethod()
    {
        return $this->_get(self::PAY_METHOD);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderInformationInterface::setDeferredPayment()
     */
    public function setDeferredPayment($deferredPayment)
    {
        return $this->setData(self::DEFERRED_PAYMENT, $deferredPayment);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderInformationInterface::getDeferredPayment()
     */
    public function getDeferredPayment()
    {
        return $this->_get(self::DEFERRED_PAYMENT);
    }

    /**
     * A function that returns product items.
     *
     * @return OrderItemInterface[]
     */
    public function getProductItems()
    {
        return $this->filterItemsByType('product');
    }

    /**
     * A function that returns product item by id item.
     *
     * @param string $itemId
     *
     * @return OrderItemInterface|null
     */
    public function getProductItemByItemId($itemId)
    {
        foreach ($this->getProductItems() as $item) {
            if ($item->getItemId() == $itemId) {
                return $item;
            }
        }
        return null;
    }

    /**
     * A function that returns shipping items.
     *
     * @return OrderItemInterface[]
     */
    public function getShippingItems()
    {
        return $this->filterItemsByType('shipping');
    }

    /**
     * A function that returns discount items.
     *
     * @return OrderItemInterface[]
     */
    public function getDiscountItems()
    {
        return $this->filterItemsByType('discount');
    }

    /**
     * A function that filters items by type.
     *
     * @param string $type
     *
     * @return OrderItemInterface[]
     */
    private function filterItemsByType($type)
    {
        $items = [];
        if (!empty($this->getItems())) {
            foreach ($this->getItems() as $item) {
                if ($item->getType() == $type) {
                    $items[] = $item;
                }
            }
        }
        return $items;
    }
}
