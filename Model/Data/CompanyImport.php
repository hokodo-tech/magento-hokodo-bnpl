<?php

/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Hokodo\BNPL\Model\Data;

use Hokodo\BNPL\Api\Data\CompanyImportInterface;
use Magento\Framework\DataObject;

class CompanyImport extends DataObject implements CompanyImportInterface
{
    /**
     * Getter for Email.
     *
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->getData(self::EMAIL);
    }

    /**
     * Setter for Email.
     *
     * @param string|null $email
     *
     * @return $this
     */
    public function setEmail(?string $email): self
    {
        $this->setData(self::EMAIL, $email);
        return $this;
    }

    /**
     * Getter for Regnumber.
     *
     * @return string|null
     */
    public function getRegNumber(): ?string
    {
        return $this->getData(self::REG_NUMBER);
    }

    /**
     * Setter for Regnumber.
     *
     * @param string|null $regNumber
     *
     * @return self
     */
    public function setRegNumber(?string $regNumber): self
    {
        $this->setData(self::REG_NUMBER, $regNumber);
        return $this;
    }

    /**
     * Getter for CountryCode.
     *
     * @return string|null
     */
    public function getCountryCode(): ?string
    {
        return $this->getData(self::COUNTRY_CODE);
    }

    /**
     * Setter for CountryCode.
     *
     * @param string|null $countryCode
     *
     * @return self
     */
    public function setCountryCode(?string $countryCode): self
    {
        $this->setData(self::COUNTRY_CODE, $countryCode);
        return $this;
    }

    /**
     * Getter for WebsiteId.
     *
     * @return int|null
     */
    public function getWebsiteId(): ?int
    {
        return $this->getData(self::WEBSITE_ID);
    }

    /**
     * Setter for WebsiteId.
     *
     * @param int|null $websiteId
     *
     * @return self
     */
    public function setWebsiteId(?int $websiteId): self
    {
        $this->setData(self::WEBSITE_ID, $websiteId);
        return $this;
    }

    /**
     * Getter for CompanyId.
     *
     * @return string|null
     */
    public function getCompanyId(): ?string
    {
        return $this->getData(self::COMPANY_ID);
    }

    /**
     * Setter for CompanyId.
     *
     * @param string|null $companyId
     *
     * @return self
     */
    public function setCompanyId(?string $companyId): self
    {
        $this->setData(self::COMPANY_ID, $companyId);
        return $this;
    }

    /**
     * Getter for OrganisationId.
     *
     * @return string|null
     */
    public function getOrganisationId(): ?string
    {
        return $this->getData(self::ORGANISATION_ID);
    }

    /**
     * Setter for OrganisationId.
     *
     * @param string|null $organisationId
     *
     * @return self
     */
    public function setOrganisationId(?string $organisationId): self
    {
        $this->setData(self::ORGANISATION_ID, $organisationId);
        return $this;
    }
}
