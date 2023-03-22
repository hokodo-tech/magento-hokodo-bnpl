<?php
/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Hokodo\BNPL\Api\Data;

/**
 * Interface Hokodo\BNPL\Api\Data\OrderInformationInterface.
 */
interface OrderInformationInterface
{
    public const ID = 'id';
    public const UNIQUE_ID = 'unique_id';
    public const PO_NUMBER = 'po_number';
    public const CUSTOMER = 'customer';
    public const CREATED = 'created';
    public const CURRENCY = 'currency';
    public const ORDER_DATE = 'order_date';
    public const INVOICE_DATE = 'invoice_date';
    public const DUE_DATE = 'due_date';
    public const PAID_DATE = 'paid_date';
    public const TOTAL_AMOUNT = 'total_amount';
    public const TAX_AMOUNT = 'tax_amount';
    public const METADATA = 'metadata';
    public const ITEMS = 'items';
    public const PAYMENT_OFFER = 'payment_offer';
    public const STATUS = 'status';
    public const PAY_METHOD = 'pay_method';
    public const DEFERRED_PAYMENT = 'deferred_payment';

    /**
     * A function that sets id.
     *
     * @param string $id
     *
     * @return $this
     */
    public function setId(string $id): self;

    /**
     * A function that gets id.
     *
     * @return string
     */
    public function getId(): string;

    /**
     * A function that sets unique id.
     *
     * @param string $uniqueId
     *
     * @return $this
     */
    public function setUniqueId(string $uniqueId): self;

    /**
     * A function that gets unique id.
     *
     * @return string
     */
    public function getUniqueId(): string;

    /**
     * A function that sets po number.
     *
     * @param string $poNumber
     *
     * @return $this
     */
    public function setPoNumber(string $poNumber): self;

    /**
     * A function that gets po number.
     *
     * @return string
     */
    public function getPoNumber(): string;

    /**
     * A function that sets customer.
     *
     * @param \Hokodo\BNPL\Api\Data\OrderCustomerInterface $customer
     *
     * @return $this
     */
    public function setCustomer(OrderCustomerInterface $customer): self;

    /**
     * A function that gets customer.
     *
     * @return \Hokodo\BNPL\Api\Data\OrderCustomerInterface
     */
    public function getCustomer(): OrderCustomerInterface;

    /**
     * A function that sets created.
     *
     * @param string $created
     *
     * @return $this
     */
    public function setCreated(string $created): self;

    /**
     * A function that gets created.
     *
     * @return string
     */
    public function getCreated(): string;

    /**
     * A function that sets currency.
     *
     * @param string $currency
     *
     * @return $this
     */
    public function setCurrency(string $currency): self;

    /**
     * A function that gets currency.
     *
     * @return string
     */
    public function getCurrency(): string;

    /**
     * A function that sets order date.
     *
     * @param string $orderDate
     *
     * @return $this
     */
    public function setOrderDate(string $orderDate): self;

    /**
     * A function that gets order date.
     *
     * @return string
     */
    public function getOrderDate(): string;

    /**
     * A function that sets invoice date.
     *
     * @param string $invoiceDate
     *
     * @return $this
     */
    public function setInvoiceDate(string $invoiceDate): self;

    /**
     * A function that gets invoice date.
     *
     * @return string
     */
    public function getInvoiceDate(): string;

    /**
     * A function that sets due date.
     *
     * @param string $dueDate
     *
     * @return $this
     */
    public function setDueDate(string $dueDate): self;

    /**
     * A function that gets due date.
     *
     * @return string
     */
    public function getDueDate(): string;

    /**
     * A function that sets paid date.
     *
     * @param string $paidDate
     *
     * @return $this
     */
    public function setPaidDate(string $paidDate): self;

    /**
     * A function that gets paid date.
     *
     * @return string
     */
    public function getPaidDate(): string;

    /**
     * A function that sets total amount.
     *
     * @param string $totalAmount
     *
     * @return $this
     */
    public function setTotalAmount(string $totalAmount): self;

    /**
     * A function that gets total amount.
     *
     * @return string
     */
    public function getTotalAmount(): string;

    /**
     * A function that sets amount.
     *
     * @param string $taxAmount
     *
     * @return $this
     */
    public function setTaxAmount(string $taxAmount): self;

    /**
     * A function that gets tax amount.
     *
     * @return string
     */
    public function getTaxAmount(): string;

    /**
     * A function that sets metadata.
     *
     * @param string[] $metadata
     *
     * @return $this
     */
    public function setMetadata(array $metadata): self;

    /**
     * A function that gets metadata.
     *
     * @return string[]
     */
    public function getMetadata(): array;

    /**
     * A function that sets items.
     *
     * @param \Hokodo\BNPL\Api\Data\OrderItemInterface[] $items
     *
     * @return $this
     */
    public function setItems(array $items): self;

    /**
     * A function that gets items.
     *
     * @return \Hokodo\BNPL\Api\Data\OrderItemInterface[]
     */
    public function getItems(): array;

    /**
     * A function that sets payment offer.
     *
     * @param string $paymentOffer
     *
     * @return $this
     */
    public function setPaymentOffer(string $paymentOffer): self;

    /**
     * A function that gets payment offer.
     *
     * @return string
     */
    public function getPaymentOffer(): string;

    /**
     * A function that sets status.
     *
     * @param string $status
     *
     * @return $this
     */
    public function setStatus(string $status): self;

    /**
     * A function that gets status.
     *
     * @return string
     */
    public function getStatus(): string;

    /**
     * A function that sets pay method.
     *
     * @param string $payMethod
     *
     * @return $this
     */
    public function setPayMethod(string $payMethod): self;

    /**
     * A function that gets pay method.
     *
     * @return string
     */
    public function getPayMethod(): string;

    /**
     * A function that sets deferred payment.
     *
     * @param string $deferredPayment
     *
     * @return $this
     */
    public function setDeferredPayment(string $deferredPayment): self;

    /**
     * A function that gets deferred payment.
     *
     * @return string
     */
    public function getDeferredPayment(): string;
}
