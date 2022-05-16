<?php

declare(strict_types=1);
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Api\Data;

/**
 * Interface PaymentLogInterface.
 *
 * @api
 */
interface PaymentLogInterface
{
    /**
     * @public string
     */
    public const ENTITY = 'hokodo_payment_logs';

    /**
     * @const string
     */
    public const PAYMENT_LOG_ID = 'payment_log_id';

    /**
     * @const string
     */
    public const PAYMENT_LOG_CONTENT = 'payment_log_content';

    /**
     * @const string
     */
    public const ACTION_TITLE = 'action_title';

    /**
     * @const string
     */
    public const STATUS = 'status';

    /**
     * @const string
     */
    public const CREATED_AT = 'created_at';

    /**
     * Get Link404 ID.
     *
     * @return int|null
     */
    public function getPaymentLogId(): ?int;

    /**
     * Get Log Content.
     *
     * @return string
     */
    public function getPaymentLogContent(): ?string;

    /**
     * Get creation time.
     *
     * @return string|null
     */
    public function getCreatedAt(): ?string;

    /**
     * Get action Title.
     *
     * @return string|null
     */
    public function getActionTitle(): ?string;

    /**
     * Get status.
     *
     * @return bool|null
     */
    public function getStatus(): ?bool;

    /**
     * Set payment log id.
     *
     * @param int $paymentLogId
     *
     * @return $this
     */
    public function setPaymentLogId(int $paymentLogId): PaymentLogInterface;

    /**
     * Set payment log content.
     *
     * @param string $paymentLogContent
     *
     * @return PaymentLogInterface
     */
    public function setPaymentLogContent(string $paymentLogContent): PaymentLogInterface;

    /**
     * Set action title.
     *
     * @param string|null $actionTitle
     *
     * @return $this
     */
    public function setActionTitle(string $actionTitle): PaymentLogInterface;

    /**
     * Set status.
     *
     * @param bool|null $status
     *
     * @return $this
     */
    public function setStatus(?bool $status): PaymentLogInterface;

    /**
     * Set creation time.
     *
     * @param string $createdAt
     *
     * @return $this
     */
    public function setCreatedAt(string $createdAt): PaymentLogInterface;
}
