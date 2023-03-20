<?php
/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Hokodo\BNPL\Api\Data\Gateway;

interface CustomerAddressInterface
{
    public const NAME = 'name';
    public const ADDRESS_LINE_ONE = 'address_line1';
    public const ADDRESS_LINE_TWO = 'address_line2';
    public const CITY = 'city';
    public const POSTCODE = 'postcode';
    public const COUNTRY = 'country';
    public const PHONE = 'phone';

    /**
     * Name setter.
     *
     * @param string $name
     *
     * @return $this
     */
    public function setName(string $name): self;

    /**
     * Address Line One setter.
     *
     * @param string $addressLineOne
     *
     * @return $this
     */
    public function setAddressLineOne(string $addressLineOne): self;

    /**
     * Address Line Two setter.
     *
     * @param string $addressLineTwo
     *
     * @return $this
     */
    public function setAddressLineTwo(string $addressLineTwo): self;

    /**
     * City setter.
     *
     * @param string $city
     *
     * @return $this
     */
    public function setCity(string $city): self;

    /**
     * Postcode setter.
     *
     * @param string $postcode
     *
     * @return $this
     */
    public function setPostcode(string $postcode): self;

    /**
     * Country setter.
     *
     * @param string $country
     *
     * @return $this
     */
    public function setCountry(string $country): self;

    /**
     * Phone setter.
     *
     * @param string $phone
     *
     * @return $this
     */
    public function setPhone(string $phone): self;
}
