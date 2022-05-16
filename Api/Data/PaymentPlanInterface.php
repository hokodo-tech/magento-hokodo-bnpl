<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Api\Data;

/**
 * Interface Hokodo\BNPL\Api\Data\PaymentPlanInterface.
 */
interface PaymentPlanInterface extends \Magento\Framework\Api\ExtensibleDataInterface
{
    public const ID = 'id';
    public const NAME = 'name';
    public const TEMPLATE = 'template';
    public const CURRENCY = 'currency';
    public const SCHEDULED_PAYMENTS = 'scheduled_payments';
    public const MERCHANT_FEE = 'merchant_fee';
    public const CUSTOMER_FEE = 'customer_fee';
    public const VALID_UNTIL = 'valid_until';
    public const PAYMENT_URL = 'payment_url';
    public const STATUS = 'status';
    public const REJECTION_REASON = 'rejection_reason';

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
     * A function that sets name.
     *
     * @param string $name
     *
     * @return $this
     */
    public function setName($name);

    /**
     * A function that gets name.
     *
     * @return string
     */
    public function getName();

    /**
     * A function that set template.
     *
     * @param string $template
     *
     * @return $this
     */
    public function setTemplate($template);

    /**
     * A function that gets template.
     *
     * @return string
     */
    public function getTemplate();

    /**
     *  A function that sets currency.
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
     * A function that sets scheduled payments.
     *
     * @param \Hokodo\BNPL\Api\Data\ScheduledPaymentsInterface[] $scheduledPayments
     *
     * @return $this
     */
    public function setScheduledPayments(array $scheduledPayments);

    /**
     * A function that gets scheduled payments.
     *
     * @return \Hokodo\BNPL\Api\Data\ScheduledPaymentsInterface[]
     */
    public function getScheduledPayments();

    /**
     * A function that sets merchant fee.
     *
     * @param string[] $merchantFee
     *
     * @return $this
     */
    public function setMerchantFee(array $merchantFee);

    /**
     * A function that gets merchant fee.
     *
     * @return string[]
     */
    public function getMerchantFee();

    /**
     * A function that sets customer fee.
     *
     * @param string[] $customerFee
     *
     * @return $this
     */
    public function setCustomerFee(array $customerFee);

    /**
     * A function that gets customer fee.
     *
     * @return string[]
     */
    public function getCustomerFee();

    /**
     * A function that sets valid until.
     *
     * @param string $validUntil
     *
     * @return $this
     */
    public function setValidUntil($validUntil);

    /**
     * A function that gets valid until.
     *
     * @return string
     */
    public function getValidUntil();

    /**
     * A function that sets payment url.
     *
     * @param string $paymentUrl
     *
     * @return $this
     */
    public function setPaymentUrl($paymentUrl);

    /**
     * A function that gets payment url.
     *
     * @return string
     */
    public function getPaymentUrl();

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
     * A function that sets rejection reason.
     *
     * @param \Hokodo\BNPL\Api\Data\RejectionReasonInterface|null $rejectionReason
     *
     * @return $this
     */
    public function setRejectionReason(
        RejectionReasonInterface $rejectionReason = null
    );

    /**
     * A function that gets rejection reason.
     *
     * @return \Hokodo\BNPL\Api\Data\RejectionReasonInterface|null
     */
    public function getRejectionReason();
}
