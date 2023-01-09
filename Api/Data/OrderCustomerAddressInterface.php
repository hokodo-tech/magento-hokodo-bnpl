<?php
/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Api\Data;

/**
 * Interface Hokodo\BNPL\Api\Data\OrderCustomerAddressInterface.
 */
interface OrderCustomerAddressInterface extends \Magento\Framework\Api\ExtensibleDataInterface
{
    public const NAME = 'name';
    public const COMPANY_NAME = 'company_name';
    public const ADDRESS_LINE1 = 'address_line1';
    public const ADDRESS_LINE2 = 'address_line2';
    public const ADDRESS_LINE3 = 'address_line3';
    public const CITY = 'city';
    public const REGION = 'region';
    public const POSTCODE = 'postcode';
    public const COUNTRY = 'country';
    public const PHONE = 'phone';
    public const EMAIL = 'email';

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
     * A function that sets company name.
     *
     * @param string $companyName
     *
     * @return $this
     */
    public function setCompanyName($companyName);

    /**
     * A function that gets company name.
     *
     * @return string
     */
    public function getCompanyName();

    /**
     * A function that sets address line 1.
     *
     * @param string $addressLine1
     *
     * @return $this
     */
    public function setAddressLine1($addressLine1);

    /**
     * A function that gets address line 1.
     *
     * @return string
     */
    public function getAddressLine1();

    /**
     * A function that sets address line 2.
     *
     * @param string $addressLine2
     *
     * @return $this
     */
    public function setAddressLine2($addressLine2);

    /**
     * A function that gets address line 2.
     *
     * @return string
     */
    public function getAddressLine2();

    /**
     * A function that sets address line 3.
     *
     * @param string $addressLine3
     *
     * @return $this
     */
    public function setAddressLine3($addressLine3);

    /**
     * A function that gets address line 3.
     *
     * @return string
     */
    public function getAddressLine3();

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
     * A function that sets region.
     *
     * @param string $region
     *
     * @return $this
     */
    public function setRegion($region);

    /**
     * A function that gets region.
     *
     * @return string
     */
    public function getRegion();

    /**
     * A function that sets post code.
     *
     * @param string $postcode
     *
     * @return $this
     */
    public function setPostcode($postcode);

    /**
     * A function that gets postcode.
     *
     * @return string
     */
    public function getPostcode();

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
     * A function that sets email.
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
}
