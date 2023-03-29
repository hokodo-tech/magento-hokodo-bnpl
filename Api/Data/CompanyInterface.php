<?php
/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Hokodo\BNPL\Api\Data;

use Magento\Framework\Api\ExtensibleDataInterface;

/**
 * Interface Hokodo\BNPL\Api\Data\CompanyInterface.
 */
interface CompanyInterface extends ExtensibleDataInterface
{
    public const URL = 'url';
    public const ID = 'id';
    public const COUNTRY = 'country';
    public const NAME = 'name';
    public const ADDRESS = 'address';
    public const CITY = 'city';
    public const POSTCODE = 'postcode';
    public const LEGAL_FORM = 'legal_form';
    public const SECTORS = 'sectors';
    public const CREATION_DATE = 'creation_date';
    public const IDENTIFIERS = 'identifiers';
    public const EMAIL = 'email';
    public const PHONE = 'phone';
    public const STATUS = 'status';
    public const ACCOUNTS_TYPE = 'accounts_type';
    public const CONFIDENCE = 'confidence';

    /**
     * A function that sets url.
     *
     * @param string|null $url
     *
     * @return CompanyInterface
     */
    public function setUrl(string $url = null): self;

    /**
     * A function that gets url.
     *
     * @return string|null
     */
    public function getUrl(): ?string;

    /**
     * A function that sets id.
     *
     * @param string|null $id
     *
     * @return CompanyInterface
     */
    public function setId(string $id = null): self;

    /**
     * A function that gets id.
     *
     * @return string|null
     */
    public function getId(): ?string;

    /**
     * A function that sets country.
     *
     * @param string|null $country
     *
     * @return CompanyInterface
     */
    public function setCountry(string $country = null): self;

    /**
     * A function that gets country.
     *
     * @return string|null
     */
    public function getCountry(): ?string;

    /**
     * A function that sets name.
     *
     * @param string|null $name
     *
     * @return CompanyInterface
     */
    public function setName(string $name = null): self;

    /**
     * A function that gets name.
     *
     * @return string|null
     */
    public function getName(): ?string;

    /**
     * A function that sets address.
     *
     * @param string|null $address
     *
     * @return CompanyInterface
     */
    public function setAddress(string $address = null): self;

    /**
     * A function that gets address.
     *
     * @return string|null
     */
    public function getAddress(): ?string;

    /**
     * A function that sets city.
     *
     * @param string|null $city
     *
     * @return CompanyInterface
     */
    public function setCity(string $city = null): self;

    /**
     * A function that gets city.
     *
     * @return string|null
     */
    public function getCity(): ?string;

    /**
     * A function that sets postcode.
     *
     * @param string|null $postcode
     *
     * @return CompanyInterface
     */
    public function setPostcode(string $postcode = null): self;

    /**
     * A function that gets postcode.
     *
     * @return string|null
     */
    public function getPostcode(): ?string;

    /**
     * A function that sets legal form.
     *
     * @param string|null $legalForm
     *
     * @return CompanyInterface
     */
    public function setLegalForm(string $legalForm = null): self;

    /**
     * A function that gets legal form.
     *
     * @return string|null
     */
    public function getLegalForm(): ?string;

    /**
     * A function that sets sectors.
     *
     * @param string[] $sectors
     *
     * @return CompanyInterface
     */
    public function setSectors(array $sectors = []);

    /**
     * A function that gets sectors.
     *
     * @return string[]
     */
    public function getSectors(): array;

    /**
     * A function that sets creation date.
     *
     * @param string|null $creationDate
     *
     * @return CompanyInterface
     */
    public function setCreationDate(string $creationDate = null): self;

    /**
     * A function that gets creation date.
     *
     * @return string|null
     */
    public function getCreationDate(): ?string;

    /**
     * A function that sets identifiers.
     *
     * @param string[] $identifiers
     *
     * @return CompanyInterface
     */
    public function setIdentifiers(array $identifiers = []): self;

    /**
     * A function that gets identifiers.
     *
     * @return string[]
     */
    public function getIdentifiers(): array;

    /**
     * A function that sets email.
     *
     * @param string|null $email
     *
     * @return CompanyInterface
     */
    public function setEmail(string $email = null): self;

    /**
     * A function that gets email.
     *
     * @return string|null
     */
    public function getEmail(): ?string;

    /**
     * A function that sets phone.
     *
     * @param string|null $phone
     *
     * @return CompanyInterface
     */
    public function setPhone(string $phone = null): self;

    /**
     * A function that gets phone.
     *
     * @return string|null
     */
    public function getPhone(): ?string;

    /**
     * A function that sets status.
     *
     * @param string|null $status
     *
     * @return CompanyInterface
     */
    public function setStatus(string $status = null): self;

    /**
     * A function that gets status.
     *
     * @return string|null
     */
    public function getStatus(): ?string;

    /**
     * A function that set account type.
     *
     * @param string|null $accountsType
     *
     * @return CompanyInterface
     */
    public function setAccountsType(string $accountsType = null): self;

    /**
     * A function that gets account type.
     *
     * @return string|null
     */
    public function getAccountsType(): ?string;

    /**
     * A function that sets confidence.
     *
     * @param string|null $confidence
     *
     * @return CompanyInterface
     */
    public function setConfidence(string $confidence = null): self;

    /**
     * A function that gets confidence.
     *
     * @return string|null
     */
    public function getConfidence(): ?string;
}
