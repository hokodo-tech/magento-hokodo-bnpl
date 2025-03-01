<?php

/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Hokodo\BNPL\Api\Data;

use Hokodo\BNPL\Api\Data\DeferredPayment\RepaymentInfoInterface;

/**
 * Interface Hokodo\BNPL\Api\Data\DeferredPaymentInterface.
 */
interface DeferredPaymentInterface
{
    public const URL = 'url';
    public const ID = 'id';
    public const NUMBER = 'number';
    public const PAYMENT_PLAN = 'payment_plan';
    public const ORDER = 'order';
    public const STATUS = 'status';
    public const REPAYMENT = 'repayment_info';

    public const STATUS_ACCEPTED = 'accepted';
    public const STATUS_PENDING = 'pending_review';
    public const STATUS_ACTION_REQUIRED = 'customer_action_required';
    public const STATUS_REJECTED = 'rejected';
    public const STATUS_CAPTURED = 'captured';
    public const STATUS_PENDING_PAYMENT = 'pending_payment';

    /**
     * A function that sets url.
     *
     * @param string|null $url
     *
     * @return $this
     */
    public function setUrl(?string $url): self;

    /**
     * A function that gets url.
     *
     * @return string|null
     */
    public function getUrl(): ?string;

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
     * A function that sets number.
     *
     * @param string|null $number
     *
     * @return $this
     */
    public function setNumber(?string $number): self;

    /**
     * A function that gets number.
     *
     * @return string|null
     */
    public function getNumber(): ?string;

    /**
     * A function that sets payment plan.
     *
     * @param string|null $paymentPlan
     *
     * @return $this
     */
    public function setPaymentPlan(?string $paymentPlan): self;

    /**
     * A function that gets payment plan.
     *
     * @return string|null
     */
    public function getPaymentPlan(): ?string;

    /**
     * A function that sets order.
     *
     * @param string|null $order
     *
     * @return $this
     */
    public function setOrder(?string $order): self;

    /**
     * A function that gets order.
     *
     * @return string|null
     */
    public function getOrder(): ?string;

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
     * Repayment Info getter.
     *
     * @return \Hokodo\BNPL\Api\Data\DeferredPayment\RepaymentInfoInterface|null
     */
    public function getRepaymentInfo(): ?RepaymentInfoInterface;

    /**
     * Repayment Info setter.
     *
     * @param \Hokodo\BNPL\Api\Data\DeferredPayment\RepaymentInfoInterface|null $repaymentInfo
     *
     * @return $this
     */
    public function setRepaymentInfo(?RepaymentInfoInterface $repaymentInfo): self;
}
