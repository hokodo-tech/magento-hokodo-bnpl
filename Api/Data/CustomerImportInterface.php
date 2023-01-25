<?php
/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Hokodo\BNPL\Api\Data;

interface CustomerImportInterface
{
    /**
     * String constants for property names.
     */
    public const EMAIL = 'email';
    public const REG_NUMBER = 'regnumber';
    public const COUNTRY_CODE = 'country_code';

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
     * @param string|null $regnumber
     *
     * @return self
     */
    public function setRegNumber(?string $regnumber): self;

    /**
     * Getter for CountryCode.
     *
     * @return string|null
     */
    public function getCountryCode(): ?string;

    /**
     * Setter for CountryCode.
     *
     * @param string|null $countrycode
     *
     * @return self
     */
    public function setCountryCode(?string $countrycode): self;
}
