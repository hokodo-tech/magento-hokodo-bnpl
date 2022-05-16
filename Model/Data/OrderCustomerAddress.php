<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Model\Data;

use Hokodo\BNPL\Api\Data\OrderCustomerAddressInterface;
use Magento\Framework\Api\AbstractSimpleObject;

/**
 * Class Hokodo\BNPL\Model\Data\OrderCustomerAddress.
 */
class OrderCustomerAddress extends AbstractSimpleObject implements OrderCustomerAddressInterface
{
    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderCustomerAddressInterface::setName()
     */
    public function setName($name)
    {
        return $this->setData(self::NAME, $name);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderCustomerAddressInterface::getName()
     */
    public function getName()
    {
        return $this->_get(self::NAME);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderCustomerAddressInterface::setCompanyName()
     */
    public function setCompanyName($companyName)
    {
        return $this->setData(self::COMPANY_NAME, $companyName);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderCustomerAddressInterface::getCompanyName()
     */
    public function getCompanyName()
    {
        return $this->_get(self::COMPANY_NAME);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderCustomerAddressInterface::setAddressLine1()
     */
    public function setAddressLine1($addressLine1)
    {
        return $this->setData(self::ADDRESS_LINE1, $addressLine1);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderCustomerAddressInterface::getAddressLine1()
     */
    public function getAddressLine1()
    {
        return $this->_get(self::ADDRESS_LINE1);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderCustomerAddressInterface::setAddressLine2()
     */
    public function setAddressLine2($addressLine2)
    {
        return $this->setData(self::ADDRESS_LINE2, $addressLine2);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderCustomerAddressInterface::getAddressLine2()
     */
    public function getAddressLine2()
    {
        return $this->_get(self::ADDRESS_LINE2);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderCustomerAddressInterface::setAddressLine3()
     */
    public function setAddressLine3($addressLine3)
    {
        return $this->setData(self::ADDRESS_LINE3, $addressLine3);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderCustomerAddressInterface::getAddressLine3()
     */
    public function getAddressLine3()
    {
        return $this->_get(self::ADDRESS_LINE3);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderCustomerAddressInterface::setCity()
     */
    public function setCity($city)
    {
        return $this->setData(self::CITY, $city);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderCustomerAddressInterface::getCity()
     */
    public function getCity()
    {
        return $this->_get(self::CITY);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderCustomerAddressInterface::setRegion()
     */
    public function setRegion($region)
    {
        return $this->setData(self::REGION, $region);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderCustomerAddressInterface::getRegion()
     */
    public function getRegion()
    {
        return $this->_get(self::REGION);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderCustomerAddressInterface::setPostcode()
     */
    public function setPostcode($postcode)
    {
        return $this->setData(self::POSTCODE, $postcode);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderCustomerAddressInterface::getPostcode()
     */
    public function getPostcode()
    {
        return $this->_get(self::POSTCODE);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderCustomerAddressInterface::setCountry()
     */
    public function setCountry($country)
    {
        return $this->setData(self::COUNTRY, $country);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderCustomerAddressInterface::getCountry()
     */
    public function getCountry()
    {
        return $this->_get(self::COUNTRY);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderCustomerAddressInterface::setPhone()
     */
    public function setPhone($phone)
    {
        return $this->setData(self::PHONE, $phone);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderCustomerAddressInterface::getPhone()
     */
    public function getPhone()
    {
        return $this->_get(self::PHONE);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderCustomerAddressInterface::setEmail()
     */
    public function setEmail($email)
    {
        return $this->setData(self::EMAIL, $email);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderCustomerAddressInterface::getEmail()
     */
    public function getEmail()
    {
        return $this->_get(self::EMAIL);
    }
}
