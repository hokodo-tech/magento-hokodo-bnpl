<?php

namespace Hokodo\BNPL\Api\Data\Company;

use Magento\Framework\Api\ExtensibleDataInterface;

interface CreditInterface extends ExtensibleDataInterface
{
    public const COMPANY = 'company';
    public const STATUS = 'status';
    public const REJECTION_REASON = 'rejection_reason';
    public const CREDIT_LIMIT = 'credit_limit';

    /**
     * Company getter.
     *
     * @return string|null
     */
    public function getCompany(): ?string;

    /**
     * Company setter.
     *
     * @param string|null $company
     *
     * @return $this
     */
    public function setCompany(string $company = null): self;

    /**
     * Status getter.
     *
     * @return string|null
     */
    public function getStatus(): ?string;

    /**
     * Status setter.
     *
     * @param string|null $status
     *
     * @return $this
     */
    public function setStatus(string $status = null): self;

    /**
     * Rejection Reason getter.
     *
     * @return string[]|null
     */
    public function getRejectionReason(): ?array;

    /**
     * Rejection Reason setter.
     *
     * @param string[]|null $rejectionReason
     *
     * @return $this
     */
    public function setRejectionReason(array $rejectionReason = null): self;

    /**
     * Credit Limit getter.
     *
     * @return \Hokodo\BNPL\Api\Data\Company\CreditLimitInterface
     */
    public function getCreditLimit(): CreditLimitInterface;

    /**
     * Credit Limit setter.
     *
     * @param \Hokodo\BNPL\Api\Data\Company\CreditLimitInterface $creditLimit
     *
     * @return $this
     */
    public function setCreditLimit(CreditLimitInterface $creditLimit): self;
}
