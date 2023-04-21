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
     * @param string|null $id
     *
     * @return $this
     */
    public function setId(?string $id): self;

    /**
     * A function that gets id.
     *
     * @return string|null
     */
    public function getId(): ?string;

    /**
     * A function that sets unique id.
     *
     * @param string|null $uniqueId
     *
     * @return $this
     */
    public function setUniqueId(?string $uniqueId): self;

    /**
     * A function that gets unique id.
     *
     * @return string|null
     */
    public function getUniqueId(): ?string;

    /**
     * A function that sets po number.
     *
     * @param string|null $poNumber
     *
     * @return $this
     */
    public function setPoNumber(?string $poNumber): self;

    /**
     * A function that gets po number.
     *
     * @return string|null
     */
    public function getPoNumber(): ?string;

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
     * @return \Hokodo\BNPL\Api\Data\OrderCustomerInterface|null
     */
    public function getCustomer(): ?OrderCustomerInterface;

    /**
     * A function that sets created.
     *
     * @param string|null $created
     *
     * @return $this
     */
    public function setCreated(?string $created): self;

    /**
     * A function that gets created.
     *
     * @return string|null
     */
    public function getCreated(): ?string;

    /**
     * A function that sets currency.
     *
     * @param string|null $currency
     *
     * @return $this
     */
    public function setCurrency(?string $currency): self;

    /**
     * A function that gets currency.
     *
     * @return string|null
     */
    public function getCurrency(): ?string;

    /**
     * A function that sets order date.
     *
     * @param string|null $orderDate
     *
     * @return $this
     */
    public function setOrderDate(?string $orderDate): self;

    /**
     * A function that gets order date.
     *
     * @return string|null
     */
    public function getOrderDate(): ?string;

    /**
     * A function that sets invoice date.
     *
     * @param string|null $invoiceDate
     *
     * @return $this
     */
    public function setInvoiceDate(?string $invoiceDate): self;

    /**
     * A function that gets invoice date.
     *
     * @return string|null
     */
    public function getInvoiceDate(): ?string;

    /**
     * A function that sets due date.
     *
     * @param string|null $dueDate
     *
     * @return $this
     */
    public function setDueDate(?string $dueDate): self;

    /**
     * A function that gets due date.
     *
     * @return string|null
     */
    public function getDueDate(): ?string;

    /**
     * A function that sets paid date.
     *
     * @param string|null $paidDate
     *
     * @return $this
     */
    public function setPaidDate(?string $paidDate): self;

    /**
     * A function that gets paid date.
     *
     * @return string|null
     */
    public function getPaidDate(): ?string;

    /**
     * A function that sets total amount.
     *
     * @param string|null $totalAmount
     *
     * @return $this
     */
    public function setTotalAmount(?string $totalAmount): self;

    /**
     * A function that gets total amount.
     *
     * @return string|null
     */
    public function getTotalAmount(): ?string;

    /**
     * A function that sets amount.
     *
     * @param string|null $taxAmount
     *
     * @return $this
     */
    public function setTaxAmount(?string $taxAmount): self;

    /**
     * A function that gets tax amount.
     *
     * @return string|null
     */
    public function getTaxAmount(): ?string;

    /**
     * A function that sets metadata.
     *
     * @param string[]|null $metadata
     *
     * @return $this
     */
    public function setMetadata(?array $metadata): self;

    /**
     * A function that gets metadata.
     *
     * @return string[]|null
     */
    public function getMetadata(): ?array;

    /**
     * A function that sets items.
     *
     * @param \Hokodo\BNPL\Api\Data\OrderItemInterface[]|null $items
     *
     * @return $this
     */
    public function setItems(?array $items): self;

    /**
     * A function that gets items.
     *
     * @return \Hokodo\BNPL\Api\Data\OrderItemInterface[]|null
     */
    public function getItems(): ?array;

    /**
     * A function that sets payment offer.
     *
     * @param string|null $paymentOffer
     *
     * @return $this
     */
    public function setPaymentOffer(?string $paymentOffer): self;

    /**
     * A function that gets payment offer.
     *
     * @return string|null
     */
    public function getPaymentOffer(): ?string;

    /**
     * A function that sets status.
     *
     * @param string|null $status
     *
     * @return $this
     */
    public function setStatus(?string $status): self;

    /**
     * A function that gets status.
     *
     * @return string|null
     */
    public function getStatus(): ?string;

    /**
     * A function that sets pay method.
     *
     * @param string|null $payMethod
     *
     * @return $this
     */
    public function setPayMethod(?string $payMethod): self;

    /**
     * A function that gets pay method.
     *
     * @return string|null
     */
    public function getPayMethod(): ?string;

    /**
     * A function that sets deferred payment.
     *
     * @param string|null $deferredPayment
     *
     * @return $this
     */
    public function setDeferredPayment(?string $deferredPayment): self;

    /**
     * A function that gets deferred payment.
     *
     * @return string|null
     */
    public function getDeferredPayment(): ?string;
}
