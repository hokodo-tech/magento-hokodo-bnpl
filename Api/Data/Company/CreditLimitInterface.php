<?php

namespace Hokodo\BNPL\Api\Data\Company;

interface CreditLimitInterface extends \Magento\Framework\Api\ExtensibleDataInterface
{
    public const CURRENCY = 'currency';
    public const AMOUNT_AVAILABLE = 'amount_available';
    public const AMOUNT_IN_USE = 'amount_in_use';
    public const AMOUNT = 'amount';
    public const REJECTION_REASON = 'rejection_reason';

    /**
     * Currency getter.
     *
     * @return string
     */
    public function getCurrency(): string;

    /**
     * Currency setter.
     *
     * @param string $currency
     *
     * @return $this
     */
    public function setCurrency(string $currency): self;

    /**
     * Amount Available getter.
     *
     * @return int|null
     */
    public function getAmountAvailable(): ?int;

    /**
     * Amount Available setter.
     *
     * @param int|null $amountAvailable
     *
     * @return $this
     */
    public function setAmountAvailable(?int $amountAvailable): self;

    /**
     * Amount In Use getter.
     *
     * @return int|null
     */
    public function getAmountInUse(): ?int;

    /**
     * Amount In Use setter.
     *
     * @param int|null $amountInUse
     *
     * @return $this
     */
    public function setAmountInUse(?int $amountInUse): self;

    /**
     * Amount getter.
     *
     * @return int|null
     */
    public function getAmount(): ?int;

    /**
     * Amount setter.
     *
     * @param int|null $amount
     *
     * @return $this
     */
    public function setAmount(?int $amount): self;

    /**
     * Rejection Reason getter.
     *
     * @return string|null
     */
    public function getRejectionReason(): ?string;

    /**
     * Rejection Reason setter.
     *
     * @param string|null $rejectionReason
     *
     * @return $this
     */
    public function setRejectionReason(?string $rejectionReason): self;
}
