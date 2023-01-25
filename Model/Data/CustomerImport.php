<?php
/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Hokodo\BNPL\Model\Data;

use Hokodo\BNPL\Api\Data\CustomerImportInterface;
use Magento\Framework\DataObject;

class CustomerImport extends DataObject implements CustomerImportInterface
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
     * @param string|null $regnumber
     *
     * @return self
     */
    public function setRegNumber(?string $regnumber): self
    {
        $this->setData(self::REG_NUMBER, $regnumber);
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
     * @param string|null $countrycode
     *
     * @return self
     */
    public function setCountryCode(?string $countrycode): self
    {
        $this->setData(self::COUNTRY_CODE, $countrycode);
        return $this;
    }
}
