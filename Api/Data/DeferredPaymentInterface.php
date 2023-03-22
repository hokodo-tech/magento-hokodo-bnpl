<?php
/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Hokodo\BNPL\Api\Data;

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

    public const STATUS_ACCEPTED = 'accepted';
    public const STATUS_PENDING = 'pending_review';
    public const STATUS_ACTION_REQUIRED = 'customer_action_required';
    public const STATUS_REJECTED = 'rejected';
    public const STATUS_CAPTURED = 'captured';
    public const STATUS_PENDING_PAYMENT = 'pending_payment';

    /**
     * A function that sets url.
     *
     * @param string $url
     *
     * @return $this
     */
    public function setUrl(string $url): self;

    /**
     * A function that gets url.
     *
     * @return string
     */
    public function getUrl(): string;

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
     * A function that sets number.
     *
     * @param string $number
     *
     * @return $this
     */
    public function setNumber(string $number): self;

    /**
     * A function that gets number.
     *
     * @return string
     */
    public function getNumber(): string;

    /**
     * A function that sets payment plan.
     *
     * @param string $paymentPlan
     *
     * @return $this
     */
    public function setPaymentPlan(string $paymentPlan): self;

    /**
     * A function that gets payment plan.
     *
     * @return string
     */
    public function getPaymentPlan(): string;

    /**
     * A function that sets order.
     *
     * @param string $order
     *
     * @return $this
     */
    public function setOrder(string $order): self;

    /**
     * A function that gets order.
     *
     * @return string
     */
    public function getOrder(): string;

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
}
