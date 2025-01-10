<?php

/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Hokodo\BNPL\Model\Data;

use Hokodo\BNPL\Api\Data\CompanyInterface;
use Magento\Framework\Api\AbstractSimpleObject;

/**
 * Class Hokodo\BNPL\Model\Data\Company.
 */
class Company extends AbstractSimpleObject implements CompanyInterface
{
    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\CompanyInterface::setUrl()
     */
    public function setUrl(?string $url): CompanyInterface
    {
        return $this->setData(self::URL, $url);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\CompanyInterface::getUrl()
     */
    public function getUrl(): ?string
    {
        return $this->_get(self::URL);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\CompanyInterface::setId()
     */
    public function setId(?string $id): CompanyInterface
    {
        return $this->setData(self::ID, $id);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\CompanyInterface::getId()
     */
    public function getId(): ?string
    {
        return $this->_get(self::ID);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\CompanyInterface::setCountry()
     */
    public function setCountry(?string $country): CompanyInterface
    {
        return $this->setData(self::COUNTRY, $country);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\CompanyInterface::getCountry()
     */
    public function getCountry(): ?string
    {
        return $this->_get(self::COUNTRY);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\CompanyInterface::setName()
     */
    public function setName(?string $name): CompanyInterface
    {
        return $this->setData(self::NAME, $name);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\CompanyInterface::getName()
     */
    public function getName(): ?string
    {
        return $this->_get(self::NAME);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\CompanyInterface::setAddress()
     */
    public function setAddress(?string $address): CompanyInterface
    {
        return $this->setData(self::ADDRESS, $address);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\CompanyInterface::getAddress()
     */
    public function getAddress(): ?string
    {
        return $this->_get(self::ADDRESS);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\CompanyInterface::setCity()
     */
    public function setCity(?string $city): CompanyInterface
    {
        return $this->setData(self::CITY, $city);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\CompanyInterface::getCity()
     */
    public function getCity(): ?string
    {
        return $this->_get(self::CITY);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\CompanyInterface::setPostcode()
     */
    public function setPostcode(?string $postcode): CompanyInterface
    {
        return $this->setData(self::POSTCODE, $postcode);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\CompanyInterface::getPostcode()
     */
    public function getPostcode(): ?string
    {
        return $this->_get(self::POSTCODE);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\CompanyInterface::setLegalForm()
     */
    public function setLegalForm(?string $legalForm): CompanyInterface
    {
        return $this->setData(self::LEGAL_FORM, $legalForm);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\CompanyInterface::getLegalForm()
     */
    public function getLegalForm(): ?string
    {
        return $this->_get(self::LEGAL_FORM);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\CompanyInterface::setSectors()
     */
    public function setSectors(?array $sectors): CompanyInterface
    {
        return $this->setData(self::SECTORS, $sectors);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\CompanyInterface::getSectors()
     */
    public function getSectors(): ?array
    {
        return $this->_get(self::SECTORS);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\CompanyInterface::setCreationDate()
     */
    public function setCreationDate(?string $creationDate): CompanyInterface
    {
        return $this->setData(self::CREATION_DATE, $creationDate);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\CompanyInterface::getCreationDate()
     */
    public function getCreationDate(): ?string
    {
        return $this->_get(self::CREATION_DATE);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\CompanyInterface::setIdentifiers()
     */
    public function setIdentifiers(?array $identifiers): CompanyInterface
    {
        return $this->setData(self::IDENTIFIERS, $identifiers);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\CompanyInterface::getIdentifiers()
     */
    public function getIdentifiers(): ?array
    {
        return $this->_get(self::IDENTIFIERS);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\CompanyInterface::setEmail()
     */
    public function setEmail(?string $email): CompanyInterface
    {
        return $this->setData(self::EMAIL, $email);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\CompanyInterface::getEmail()
     */
    public function getEmail(): ?string
    {
        return $this->_get(self::EMAIL);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\CompanyInterface::setPhone()
     */
    public function setPhone(?string $phone): CompanyInterface
    {
        return $this->setData(self::PHONE, $phone);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\CompanyInterface::getPhone()
     */
    public function getPhone(): ?string
    {
        return $this->_get(self::PHONE);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\CompanyInterface::setStatus()
     */
    public function setStatus(?string $status): CompanyInterface
    {
        return $this->setData(self::STATUS, $status);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\CompanyInterface::getStatus()
     */
    public function getStatus(): ?string
    {
        return $this->_get(self::STATUS);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\CompanyInterface::setAccountsType()
     */
    public function setAccountsType(?string $accountsType): CompanyInterface
    {
        return $this->setData(self::ACCOUNTS_TYPE, $accountsType);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\CompanyInterface::getAccountsType()
     */
    public function getAccountsType(): ?string
    {
        return $this->_get(self::ACCOUNTS_TYPE);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\CompanyInterface::setConfidence()
     */
    public function setConfidence(?string $confidence): CompanyInterface
    {
        return $this->setData(self::CONFIDENCE, $confidence);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\CompanyInterface::getConfidence()
     */
    public function getConfidence(): ?string
    {
        return $this->_get(self::CONFIDENCE);
    }
}
