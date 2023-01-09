<?php
/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Api\Data;

/**
 * Interface Hokodo\BNPL\Api\Data\HokodoOrganisationInterface.
 */
interface HokodoOrganisationInterface
{
    /**#@+
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    public const ORGANISATION_ID = 'organisation_id';
    public const API_ID = 'api_id';
    public const COUNTRY = 'country';
    public const NAME = 'name';
    public const ADDRESS = 'address';
    public const CITY = 'city';
    public const POSTCODE = 'postcode';
    public const EMAIL = 'email';
    public const PHONE = 'phone';
    public const COMPANY_API_ID = 'company_api_id';
    public const CREATED_AT = 'created_at';

    /**
     * A function that sets organisation id.
     *
     * @param int $organisationId
     *
     * @return $this
     */
    public function setOrganisationId($organisationId);

    /**
     * A function that gets organisation id.
     *
     * @return int|null
     */
    public function getOrganisationId();

    /**
     * A function that sets api id.
     *
     * @param string $apiId
     *
     * @return $this
     */
    public function setApiId($apiId);

    /**
     * A function that gets api id.
     *
     * @return string|null
     */
    public function getApiId();

    /**
     * A function that sets country.
     *
     * @param string $country
     *
     * @return $this
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
     * @return $this
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
     * @return $this
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
     * @return $this
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
     * @return $this
     */
    public function setPostcode($postcode);

    /**
     * A function that get postcode.
     *
     * @return string
     */
    public function getPostcode();

    /**
     * A function that set email.
     *
     * @param string $email
     *
     * @return $this
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
     * @return $this
     */
    public function setPhone($phone);

    /**
     * A function that gets phone.
     *
     * @return string
     */
    public function getPhone();

    /**
     * A function that sets company api id.
     *
     * @param string $companyApiId
     *
     * @return $this
     */
    public function setCompanyApiId($companyApiId);

    /**
     * A function that gets company api id.
     *
     * @return string|null
     */
    public function getCompanyApiId();

    /**
     * A function that sets created at.
     *
     * @param string $createdAt
     *
     * @return $this
     */
    public function setCreatedAt($createdAt);

    /**
     * A function that gets created at.
     *
     * @return string|null
     */
    public function getCreatedAt();
}
