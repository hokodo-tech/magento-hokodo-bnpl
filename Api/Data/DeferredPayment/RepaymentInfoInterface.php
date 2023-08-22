<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Hokodo\BNPL\Api\Data\DeferredPayment;

use Magento\Framework\Api\ExtensibleDataInterface;

interface RepaymentInfoInterface extends ExtensibleDataInterface
{
    public const STATUS = 'status';
    public const OUTSTANDING_AMOUNT = 'outstanding_amount';

    public const STATUS_PAID = 'paid';
    public const STATUS_PARTIAL = 'partially_paid';
    public const STATUS_UNPAID = 'unpaid';

    /**
     * Status getter.
     *
     * @return string
     */
    public function getStatus(): string;

    /**
     * Status setter.
     *
     * @param string $status
     *
     * @return $this
     */
    public function setStatus(string $status): self;

    /**
     * Outstanding Amount getter.
     *
     * @return int
     */
    public function getOutstandingAmount(): int;

    /**
     * Outstanding Amount setter.
     *
     * @param int $outstandingAmount
     *
     * @return $this
     */
    public function setOutstandingAmount(int $outstandingAmount): self;
}
