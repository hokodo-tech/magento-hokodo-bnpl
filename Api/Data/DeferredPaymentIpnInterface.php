<?php
/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Api\Data;

/**
 * Interface Hokodo\BNPL\Api\Data\DeferredPaymentIpnInterface.
 */
interface DeferredPaymentIpnInterface extends \Magento\Framework\Api\ExtensibleDataInterface
{
    public const URL = 'url';
    public const ID = 'id';
    public const NUMBER = 'number';
    public const PAYMENT_PLAN = 'payment_plan';
    public const ORDER = 'order';
    public const OUTSTANDING_BALANCE = 'outstanding_balance';
    public const NEXT_PAYMENT_DATE = 'next_payment_date';
    public const PAYMENTS = 'payments';
    public const STATUS = 'status';

    /**
     * A function that sets url.
     *
     * @param string $url
     *
     * @return $this
     */
    public function setUrl($url);

    /**
     * A function that gets url.
     *
     * @return string
     */
    public function getUrl();

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
     * A function that sets number.
     *
     * @param string $number
     *
     * @return $this
     */
    public function setNumber($number);

    /**
     * A function that gets number.
     *
     * @return string
     */
    public function getNumber();

    /**
     * A function that sets payment plan.
     *
     * @param \Hokodo\BNPL\Api\Data\PaymentPlanInterface $paymentPlan
     *
     * @return $this
     */
    public function setPaymentPlan(PaymentPlanInterface $paymentPlan);

    /**
     * A function that gets payment plan.
     *
     * @return \Hokodo\BNPL\Api\Data\PaymentPlanInterface
     */
    public function getPaymentPlan();

    /**
     * A function that sets outstanding balance.
     *
     * @param string[] $outstandingBalance
     *
     * @return $this
     */
    public function setOutstandingBalance(array $outstandingBalance);

    /**
     * A function that gets outstanding balance.
     *
     * @return string[]
     */
    public function getOutstandingBalance();

    /**
     * A function that sets next payment date.
     *
     * @param string $nextPaymentDate
     *
     * @return $this
     */
    public function setNextPaymentDate($nextPaymentDate);

    /**
     * A function that gets next payment date.
     *
     * @return string
     */
    public function getNextPaymentDate();

    /**
     * A function that sets payment.
     *
     * @param string $payments
     *
     * @return $this
     */
    public function setPayments($payments);

    /**
     * A function that gets payment.
     *
     * @return string
     */
    public function getPayments();

    /**
     * A function that sets order.
     *
     * @param string $order
     *
     * @return $this
     */
    public function setOrder($order);

    /**
     * A function that gets order.
     *
     * @return string
     */
    public function getOrder();

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
}
