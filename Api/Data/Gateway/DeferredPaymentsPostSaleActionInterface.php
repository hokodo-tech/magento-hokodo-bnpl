<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Hokodo\BNPL\Api\Data\Gateway;

interface DeferredPaymentsPostSaleActionInterface
{
    public const PAYMENT_ID = 'payment_id';
    public const TYPE = 'type';
    public const AMOUNT = 'amount';
    public const METADATA = 'metadata';

    public const TYPE_CAPTURE = 'capture';
    public const TYPE_CAPTURE_REMAINING = 'capture_remaining';
    public const TYPE_REFUND = 'refund';
    public const TYPE_VOID = 'void';
    public const TYPE_VOID_REMAINING = 'void_remaining';

    /**
     * Payment Id setter.
     *
     * @param string $paymentId
     *
     * @return $this
     */
    public function setPaymentId(string $paymentId): self;

    /**
     * Amount setter.
     *
     * @param int $amount
     *
     * @return $this
     */
    public function setAmount(int $amount): self;

    /**
     * Type setter.
     *
     * @param string $type
     *
     * @return $this
     */
    public function setType(string $type): self;

    /**
     * Metadata getter.
     *
     * @return array
     */
    public function getMetadata(): array;

    /**
     * Metadata setter.
     *
     * @param array $metadata
     *
     * @return $this
     */
    public function setMetadata(array $metadata): self;
}
