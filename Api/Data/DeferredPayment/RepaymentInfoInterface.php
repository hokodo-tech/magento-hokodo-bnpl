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
     * @return string
     */
    public function getOutstandingAmount(): string;

    /**
     * Outstanding Amount setter.
     *
     * @param string $outstandingAmount
     *
     * @return $this
     */
    public function setOutstandingAmount(string $outstandingAmount): self;
}
