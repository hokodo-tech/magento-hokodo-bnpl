<?php
/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Hokodo\BNPL\Model\Data\Gateway;

use Hokodo\BNPL\Api\Data\Gateway\CustomerAddressInterface;
use Magento\Framework\Api\AbstractSimpleObject;

class CustomerAddress extends AbstractSimpleObject implements CustomerAddressInterface
{
    /**
     * @inheritdoc
     */
    public function setName(string $name): self
    {
        $this->setData(self::NAME, $name);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setAddressLineOne(string $addressLineOne): self
    {
        $this->setData(self::ADDRESS_LINE_ONE, $addressLineOne);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setAddressLineTwo(string $addressLineTwo): self
    {
        $this->setData(self::ADDRESS_LINE_TWO, $addressLineTwo);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setCity(string $city): self
    {
        $this->setData(self::CITY, $city);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setPostcode(string $postcode): self
    {
        $this->setData(self::POSTCODE, $postcode);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setCountry(string $country): self
    {
        $this->setData(self::COUNTRY, $country);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setPhone(string $phone): self
    {
        $this->setData(self::PHONE, $phone);
        return $this;
    }
}
