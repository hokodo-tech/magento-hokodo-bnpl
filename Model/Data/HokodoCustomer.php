<?php

/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Hokodo\BNPL\Model\Data;

use Hokodo\BNPL\Api\Data\Company\CreditLimitInterface;
use Hokodo\BNPL\Api\Data\HokodoCustomerInterface;
use Magento\Framework\DataObject;

class HokodoCustomer extends DataObject implements HokodoCustomerInterface
{
    /**
     * @inheritdoc
     */
    public function getId(): ?int
    {
        return $this->getData(self::ID) ? (int) $this->getData(self::ID) : null;
    }

    /**
     * @inheritdoc
     */
    public function setId(int $id): self
    {
        $this->setData(self::ID, $id);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getCustomerId(): ?int
    {
        $id = $this->getData(self::CUSTOMER_ID);
        return $id ? (int) $id : null;
    }

    /**
     * @inheritdoc
     */
    public function setCustomerId(int $customerId): self
    {
        $this->setData(self::CUSTOMER_ID, $customerId);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getCompanyId(): ?string
    {
        return $this->getData(self::COMPANY_ID);
    }

    /**
     * @inheritdoc
     */
    public function setCompanyId(?string $companyId): self
    {
        $this->setData(self::COMPANY_ID, $companyId);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getUserId(): ?string
    {
        return $this->getData(self::USER_ID);
    }

    /**
     * @inheritdoc
     */
    public function setUserId(string $userId): self
    {
        $this->setData(self::USER_ID, $userId);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getOrganisationId(): ?string
    {
        return $this->getData(self::ORGANISATION_ID);
    }

    /**
     * @inheritdoc
     */
    public function setOrganisationId(string $organisationId): self
    {
        $this->setData(self::ORGANISATION_ID, $organisationId);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getCreditLimit(): ?CreditLimitInterface
    {
        return $this->getData(self::CREDIT_LIMIT);
    }

    /**
     * @inheritdoc
     */
    public function setCreditLimit(?CreditLimitInterface $credit): self
    {
        $this->setData(self::CREDIT_LIMIT, $credit);
        return $this;
    }
}
