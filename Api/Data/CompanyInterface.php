<?php
/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Api\Data;

/**
 * Interface Hokodo\BNPL\Api\Data\CompanyInterface.
 */
interface CompanyInterface extends \Magento\Framework\Api\ExtensibleDataInterface
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
     * @param string $url
     *
     * @return CompanyInterface
     */
    public function setUrl($url);

    /**
     * A function that gets url.
     *
     * @return string
     */
    public function getUrl();

    /**
     * A function that sets id.
     *
     * @param string $id
     *
     * @return CompanyInterface
     */
    public function setId($id);

    /**
     * A function that gets id.
     *
     * @return string
     */
    public function getId();

    /**
     * A function that sets country.
     *
     * @param string $country
     *
     * @return CompanyInterface
     */
    public function setCountry($country);

    /**
     * A function that gets country.
     *
     * @return string
     */
    public function getCountry();

    /**
     * A function that sets name.
     *
     * @param string $name
     *
     * @return CompanyInterface
     */
    public function setName($name);

    /**
     * A function that gets name.
     *
     * @return string
     */
    public function getName();

    /**
     * A function that sets address.
     *
     * @param string $address
     *
     * @return CompanyInterface
     */
    public function setAddress($address);

    /**
     * A function that gets address.
     *
     * @return string
     */
    public function getAddress();

    /**
     * A function that sets city.
     *
     * @param string $city
     *
     * @return CompanyInterface
     */
    public function setCity($city);

    /**
     * A function that gets city.
     *
     * @return string
     */
    public function getCity();

    /**
     * A function that sets postcode.
     *
     * @param string $postcode
     *
     * @return CompanyInterface
     */
    public function setPostcode($postcode);

    /**
     * A function that gets postcode.
     *
     * @return string
     */
    public function getPostcode();

    /**
     * A function that sets legal form.
     *
     * @param string $legalForm
     *
     * @return CompanyInterface
     */
    public function setLegalForm($legalForm);

    /**
     * A function that gets legal form.
     *
     * @return string
     */
    public function getLegalForm();

    /**
     * A function that sets sectors.
     *
     * @param string[] $sectors
     *
     * @return CompanyInterface
     */
    public function setSectors(array $sectors);

    /**
     * A function that gets sectors.
     *
     * @return string[]
     */
    public function getSectors();

    /**
     * A function that sets creation date.
     *
     * @param string $creationDate
     *
     * @return CompanyInterface
     */
    public function setCreationDate($creationDate);

    /**
     * A function that gets creation date.
     *
     * @return string
     */
    public function getCreationDate();

    /**
     * A function that sets identifiers.
     *
     * @param string[] $identifiers
     *
     * @return CompanyInterface
     */
    public function setIdentifiers(array $identifiers);

    /**
     * A function that gets identifiers.
     *
     * @return string[]
     */
    public function getIdentifiers();

    /**
     * A function that sets email.
     *
     * @param string $email
     *
     * @return CompanyInterface
     */
    public function setEmail($email);

    /**
     * A function that gets email.
     *
     * @return string
     */
    public function getEmail();

    /**
     * A function that sets phone.
     *
     * @param string $phone
     *
     * @return CompanyInterface
     */
    public function setPhone($phone);

    /**
     * A function that gets phone.
     *
     * @return string
     */
    public function getPhone();

    /**
     * A function that sets status.
     *
     * @param string $status
     *
     * @return CompanyInterface
     */
    public function setStatus($status);

    /**
     * A function that gets status.
     *
     * @return string
     */
    public function getStatus();

    /**
     * A function that set account type.
     *
     * @param string $accountsType
     *
     * @return CompanyInterface
     */
    public function setAccountsType($accountsType);

    /**
     * A function that gets account type.
     *
     * @return string
     */
    public function getAccountsType();

    /**
     * A function that sets confidence.
     *
     * @param string $confidence
     *
     * @return CompanyInterface
     */
    public function setConfidence($confidence);

    /**
     * A function that gets confidence.
     *
     * @return string
     */
    public function getConfidence();
}
