<?php

declare(strict_types=1);

namespace Hokodo\BNPL\Model\Data\Company;

use Hokodo\BNPL\Api\Data\Company\CreditInterface;
use Hokodo\BNPL\Api\Data\Company\CreditLimitInterface;
use Magento\Framework\DataObject;

class Credit extends DataObject implements CreditInterface
{
    /**
     * @inheritdoc
     */
    public function getCompany(): string
    {
        return $this->getData(self::COMPANY);
    }

    /**
     * @inheritdoc
     */
    public function setCompany(string $company): self
    {
        $this->setData(self::COMPANY, $company);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getStatus(): string
    {
        return $this->getData(self::STATUS);
    }

    /**
     * @inheritdoc
     */
    public function setStatus(string $status): self
    {
        $this->setData(self::STATUS, $status);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getRejectionReason(): ?array
    {
        return $this->getData(self::REJECTION_REASON);
    }

    /**
     * @inheritdoc
     */
    public function setRejectionReason(?array $rejectionReason): self
    {
        $this->setData(self::REJECTION_REASON, $rejectionReason);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getCreditLimit(): CreditLimitInterface
    {
        return $this->getData(self::CREDIT_LIMIT);
    }

    /**
     * @inheritdoc
     */
    public function setCreditLimit(CreditLimitInterface $creditLimit): self
    {
        $this->setData(self::CREDIT_LIMIT, $creditLimit);
        return $this;
    }
}
