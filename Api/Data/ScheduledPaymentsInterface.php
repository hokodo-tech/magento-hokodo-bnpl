<?php
/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Api\Data;

/**
 * Interface Hokodo\BNPL\Api\Data\ScheduledPaymentsInterface.
 */
interface ScheduledPaymentsInterface extends \Magento\Framework\Api\ExtensibleDataInterface
{
    public const DATE = 'date';
    public const AMOUNT = 'amount';
    public const ALLOWED_PAYMENT_METHODS = 'allowed_payment_methods';
    public const PAYMENT_METHOD = 'payment_method';

    /**
     * A function that sets date.
     *
     * @param string $date
     *
     * @return $this
     */
    public function setDate($date);

    /**
     * A function that gets date.
     *
     * @return string
     */
    public function getDate();

    /**
     * A function that sets amount.
     *
     * @param string $amount
     *
     * @return $this
     */
    public function setAmount($amount);

    /**
     * A function that gets amount.
     *
     * @return string
     */
    public function getAmount();

    /**
     * A function that sets payment method.
     *
     * @param \Hokodo\BNPL\Api\Data\PaymentMethodInterface $paymentMethod
     *
     * @return $this
     */
    public function setPaymentMethod($paymentMethod);

    /**
     * A function that gets payment method.
     *
     * @return \Hokodo\BNPL\Api\Data\PaymentMethodInterface
     */
    public function getPaymentMethod();

    /**
     * A function that sets allowed payment methods.
     *
     * @param \Hokodo\BNPL\Api\Data\PaymentMethodInterface[] $allowedPaymentMethods
     *
     * @return $this
     */
    public function setAllowedPaymentMethods(array $allowedPaymentMethods);

    /**
     * A function that gets allowed payment methods.
     *
     * @return \Hokodo\BNPL\Api\Data\PaymentMethodInterface[]
     */
    public function getAllowedPaymentMethods();
}
