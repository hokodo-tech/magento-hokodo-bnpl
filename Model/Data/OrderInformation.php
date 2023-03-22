<?php
/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Hokodo\BNPL\Model\Data;

use Hokodo\BNPL\Api\Data\OrderCustomerInterface;
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
    public function setId(string $id): OrderInformationInterface
    {
        return $this->setData(self::ID, $id);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderInformationInterface::getId()
     */
    public function getId(): string
    {
        return $this->_get(self::ID);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderInformationInterface::setUniqueId()
     */
    public function setUniqueId(string $uniqueId): OrderInformationInterface
    {
        return $this->setData(self::UNIQUE_ID, $uniqueId);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderInformationInterface::getUniqueId()
     */
    public function getUniqueId(): string
    {
        return $this->_get(self::UNIQUE_ID);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderInformationInterface::setPoNumber()
     */
    public function setPoNumber(string $poNumber): OrderInformationInterface
    {
        return $this->setData(self::PO_NUMBER, $poNumber);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderInformationInterface::getPoNumber()
     */
    public function getPoNumber(): string
    {
        return $this->_get(self::PO_NUMBER);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderInformationInterface::setCustomer()
     */
    public function setCustomer(OrderCustomerInterface $customer): OrderInformationInterface
    {
        return $this->setData(self::CUSTOMER, $customer);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderInformationInterface::getCustomer()
     */
    public function getCustomer(): OrderCustomerInterface
    {
        return $this->_get(self::CUSTOMER);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderInformationInterface::setCreated()
     */
    public function setCreated(string $created): OrderInformationInterface
    {
        return $this->setData(self::CREATED, $created);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderInformationInterface::getCreated()
     */
    public function getCreated(): string
    {
        return $this->_get(self::CREATED);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderInformationInterface::setCurrency()
     */
    public function setCurrency(string $currency): OrderInformationInterface
    {
        return $this->setData(self::CURRENCY, $currency);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderInformationInterface::getCurrency()
     */
    public function getCurrency(): string
    {
        return $this->_get(self::CURRENCY);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderInformationInterface::setOrderDate()
     */
    public function setOrderDate(string $orderDate): OrderInformationInterface
    {
        return $this->setData(self::ORDER_DATE, $orderDate);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderInformationInterface::getOrderDate()
     */
    public function getOrderDate(): string
    {
        return $this->_get(self::ORDER_DATE);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderInformationInterface::setInvoiceDate()
     */
    public function setInvoiceDate(string $invoiceDate): OrderInformationInterface
    {
        return $this->setData(self::INVOICE_DATE, $invoiceDate);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderInformationInterface::getInvoiceDate()
     */
    public function getInvoiceDate(): string
    {
        return $this->_get(self::INVOICE_DATE);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderInformationInterface::setDueDate()
     */
    public function setDueDate(string $dueDate): OrderInformationInterface
    {
        return $this->setData(self::DUE_DATE, $dueDate);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderInformationInterface::getDueDate()
     */
    public function getDueDate(): string
    {
        return $this->_get(self::DUE_DATE);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderInformationInterface::setPaidDate()
     */
    public function setPaidDate(string $paidDate): OrderInformationInterface
    {
        return $this->setData(self::PAID_DATE, $paidDate);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderInformationInterface::getPaidDate()
     */
    public function getPaidDate(): string
    {
        return $this->_get(self::PAID_DATE);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderInformationInterface::setTotalAmount()
     */
    public function setTotalAmount(string $totalAmount): OrderInformationInterface
    {
        return $this->setData(self::TOTAL_AMOUNT, $totalAmount);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderInformationInterface::getTotalAmount()
     */
    public function getTotalAmount(): string
    {
        return $this->_get(self::TOTAL_AMOUNT);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderInformationInterface::setTaxAmount()
     */
    public function setTaxAmount(string $taxAmount): OrderInformationInterface
    {
        return $this->setData(self::TAX_AMOUNT, $taxAmount);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderInformationInterface::getTaxAmount()
     */
    public function getTaxAmount(): string
    {
        return $this->_get(self::TAX_AMOUNT);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderInformationInterface::setMetadata()
     */
    public function setMetadata(array $metadata): OrderInformationInterface
    {
        return $this->setData(self::METADATA, $metadata);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderInformationInterface::getMetadata()
     */
    public function getMetadata(): array
    {
        return $this->_get(self::METADATA);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderInformationInterface::setItems()
     */
    public function setItems(array $items): OrderInformationInterface
    {
        return $this->setData(self::ITEMS, $items);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderInformationInterface::getItems()
     */
    public function getItems(): array
    {
        return $this->_get(self::ITEMS);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderInformationInterface::setPaymentOffer()
     */
    public function setPaymentOffer(string $paymentOffer): OrderInformationInterface
    {
        return $this->setData(self::PAYMENT_OFFER, $paymentOffer);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderInformationInterface::getPaymentOffer()
     */
    public function getPaymentOffer(): string
    {
        return $this->_get(self::PAYMENT_OFFER);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderInformationInterface::setStatus()
     */
    public function setStatus(string $status): OrderInformationInterface
    {
        return $this->setData(self::STATUS, $status);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderInformationInterface::getStatus()
     */
    public function getStatus(): string
    {
        return $this->_get(self::STATUS);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderInformationInterface::setPayMethod()
     */
    public function setPayMethod(string $payMethod): OrderInformationInterface
    {
        return $this->setData(self::PAY_METHOD, $payMethod);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderInformationInterface::getPayMethod()
     */
    public function getPayMethod(): string
    {
        return $this->_get(self::PAY_METHOD);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderInformationInterface::setDeferredPayment()
     */
    public function setDeferredPayment(string $deferredPayment): OrderInformationInterface
    {
        return $this->setData(self::DEFERRED_PAYMENT, $deferredPayment);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderInformationInterface::getDeferredPayment()
     */
    public function getDeferredPayment(): string
    {
        return $this->_get(self::DEFERRED_PAYMENT);
    }

    /**
     * A function that returns product items.
     *
     * @return OrderItemInterface[]
     */
    public function getProductItems(): array
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
    public function getProductItemByItemId($itemId): ?OrderItemInterface
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
    public function getShippingItems(): array
    {
        return $this->filterItemsByType('shipping');
    }

    /**
     * A function that returns discount items.
     *
     * @return OrderItemInterface[]
     */
    public function getDiscountItems(): array
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
    private function filterItemsByType($type): array
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
