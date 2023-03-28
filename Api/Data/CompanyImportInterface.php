<?php
/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Hokodo\BNPL\Api\Data;

interface CompanyImportInterface
{
    /**
     * String constants for property names.
     */
    public const EMAIL = 'email';
    public const REG_NUMBER = 'reg_number';
    public const COUNTRY_CODE = 'country_code';
    public const WEBSITE_ID = 'website_id';

    /**
     * Getter for Email.
     *
     * @return string|null
     */
    public function getEmail(): ?string;

    /**
     * Setter for Email.
     *
     * @param string|null $email
     *
     * @return self
     */
    public function setEmail(?string $email): self;

    /**
     * Getter for Regnumber.
     *
     * @return string|null
     */
    public function getRegNumber(): ?string;

    /**
     * Setter for Regnumber.
     *
     * @param string|null $regNumber
     *
     * @return self
     */
    public function setRegNumber(?string $regNumber): self;

    /**
     * Getter for CountryCode.
     *
     * @return string|null
     */
    public function getCountryCode(): ?string;

    /**
     * Setter for CountryCode.
     *
     * @param string|null $countryCode
     *
     * @return self
     */
    public function setCountryCode(?string $countryCode): self;

    /**
     * Getter for WebsiteId.
     *
     * @return int|null
     */
    public function getWebsiteId(): ?int;

    /**
     * Setter for WebsiteId.
     *
     * @param int|null $websiteId
     *
     * @return self
     */
    public function setWebsiteId(?int $websiteId): self;
}
