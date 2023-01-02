<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Api\Data;

/**
 * Interface Hokodo\BNPL\Api\Data\OrderIpnInterface.
 */
interface OrderIpnInterface extends \Magento\Framework\Api\ExtensibleDataInterface
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
    public function setId($id);

    /**
     * A function that gets id.
     *
     * @return string
     */
    public function getId();

    /**
     * A function that sets unique id.
     *
     * @param string $uniqueId
     *
     * @return $this
     */
    public function setUniqueId($uniqueId);

    /**
     * A function that gets unique id.
     *
     * @return string
     */
    public function getUniqueId();

    /**
     * A function that sets po number.
     *
     * @param string $poNumber
     *
     * @return $this
     */
    public function setPoNumber($poNumber);

    /**
     * A function that gets po number.
     *
     * @return string
     */
    public function getPoNumber();

    /**
     * A function that sets customer.
     *
     * @param \Hokodo\BNPL\Api\Data\OrderCustomerIpnInterface $customer
     *
     * @return $this
     */
    public function setCustomer(OrderCustomerIpnInterface $customer);

    /**
     * A function that gets customer.
     *
     * @return \Hokodo\BNPL\Api\Data\OrderCustomerIpnInterface
     */
    public function getCustomer();

    /**
     * A function that sets created.
     *
     * @param string $created
     *
     * @return $this
     */
    public function setCreated($created);

    /**
     * A function that gets created.
     *
     * @return string
     */
    public function getCreated();

    /**
     * A function that sets currency.
     *
     * @param string $currency
     *
     * @return $this
     */
    public function setCurrency($currency);

    /**
     * A function that gets currency.
     *
     * @return string
     */
    public function getCurrency();

    /**
     * A function that sets order date.
     *
     * @param string $orderDate
     *
     * @return $this
     */
    public function setOrderDate($orderDate);

    /**
     * A function that gets order date.
     *
     * @return string
     */
    public function getOrderDate();

    /**
     * A function that sets invoice date.
     *
     * @param string $invoiceDate
     *
     * @return $this
     */
    public function setInvoiceDate($invoiceDate);

    /**
     * A function that gets invoice date.
     *
     * @return string
     */
    public function getInvoiceDate();

    /**
     * A function that sets due date.
     *
     * @param string $dueDate
     *
     * @return $this
     */
    public function setDueDate($dueDate);

    /**
     * A function that gets due date.
     *
     * @return string
     */
    public function getDueDate();

    /**
     * A function that sets paid date.
     *
     * @param string $paidDate
     *
     * @return $this
     */
    public function setPaidDate($paidDate);

    /**
     * A function that gets paid date.
     *
     * @return string
     */
    public function getPaidDate();

    /**
     * A function that sets total amount.
     *
     * @param string $totalAmount
     *
     * @return $this
     */
    public function setTotalAmount($totalAmount);

    /**
     * A function that gets total amount.
     *
     * @return string
     */
    public function getTotalAmount();

    /**
     * A function that sets tax amount.
     *
     * @param string $taxAmount
     *
     * @return $this
     */
    public function setTaxAmount($taxAmount);

    /**
     * A function that gets tax amount.
     *
     * @return string
     */
    public function getTaxAmount();

    /**
     * A function that sets metadata.
     *
     * @param string[] $metadata
     *
     * @return $this
     */
    public function setMetadata(array $metadata);

    /**
     * A function that gets metadata.
     *
     * @return string[]
     */
    public function getMetadata();

    /**
     * A function that sets items.
     *
     * @param \Hokodo\BNPL\Api\Data\OrderItemInterface[] $items
     *
     * @return $this
     */
    public function setItems(array $items);

    /**
     * A function that gets items.
     *
     * @return \Hokodo\BNPL\Api\Data\OrderItemInterface[]
     */
    public function getItems();

    /**
     * A function that sets payment offer.
     *
     * @param \Hokodo\BNPL\Api\Data\PaymentOffersInterface|null $paymentOffer
     *
     * @return $this
     */
    public function setPaymentOffer(?PaymentOffersInterface $paymentOffer);

    /**
     * A function that gets payment offer.
     *
     * @return \Hokodo\BNPL\Api\Data\PaymentOffersInterface
     */
    public function getPaymentOffer();

    /**
     * A function that sets status.
     *
     * @param string $status
     *
     * @return $this
     */
    public function setStatus($status);

    /**
     * A function that gets status.
     *
     * @return string
     */
    public function getStatus();

    /**
     * A function that set pay method.
     *
     * @param string $payMethod
     *
     * @return $this
     */
    public function setPayMethod($payMethod);

    /**
     * A function that gets pay method.
     *
     * @return string
     */
    public function getPayMethod();

    /**
     * A function that sets deferred payment.
     *
     * @param DeferredPaymentIpnInterface|null $deferredPayment
     *
     * @return $this
     */
    public function setDeferredPayment(?DeferredPaymentIpnInterface $deferredPayment);

    /**
     * A function that gets deferred payment.
     *
     * @return \Hokodo\BNPL\Api\Data\DeferredPaymentIpnInterface
     */
    public function getDeferredPayment();
}
