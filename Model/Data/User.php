<?php
/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Hokodo\BNPL\Model\Data;

use Hokodo\BNPL\Api\Data\UserInterface;
use Magento\Framework\Api\AbstractSimpleObject;

/**
 * Class Hokodo\BNPL\Model\Data\User.
 */
class User extends AbstractSimpleObject implements UserInterface
{
    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\UserInterface::setId()
     */
    public function setId(?string $id): UserInterface
    {
        return $this->setData(self::ID, $id);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\UserInterface::getId()
     */
    public function getId(): ?string
    {
        return $this->_get(self::ID);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\UserInterface::setEmail()
     */
    public function setEmail(?string $email): UserInterface
    {
        return $this->setData(self::EMAIL, $email);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\UserInterface::getEmail()
     */
    public function getEmail(): ?string
    {
        return $this->_get(self::EMAIL);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\UserInterface::setEmailValidated()
     */
    public function setEmailValidated(?bool $emailValidated): UserInterface
    {
        return $this->setData(self::EMAIL_VALIDATED, $emailValidated);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\UserInterface::getEmailValidated()
     */
    public function getEmailValidated(): bool
    {
        return (bool) $this->_get(self::EMAIL_VALIDATED);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\UserInterface::setUniqueId()
     */
    public function setUniqueId(?string $uniqueId): UserInterface
    {
        return $this->setData(self::UNIQUE_ID, $uniqueId);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\UserInterface::getUniqueId()
     */
    public function getUniqueId(): ?string
    {
        return $this->_get(self::UNIQUE_ID);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\UserInterface::setName()
     */
    public function setName(?string $name): UserInterface
    {
        return $this->setData(self::NAME, $name);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\UserInterface::getName()
     */
    public function getName(): ?string
    {
        return $this->_get(self::NAME);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\UserInterface::setPhone()
     */
    public function setPhone(?string $phone): UserInterface
    {
        return $this->setData(self::PHONE, $phone);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\UserInterface::getPhone()
     */
    public function getPhone(): ?string
    {
        return $this->_get(self::PHONE);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\UserInterface::setRegistered()
     */
    public function setRegistered(?string $registered): UserInterface
    {
        return $this->setData(self::REGISTERED, $registered);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\UserInterface::getRegistered()
     */
    public function getRegistered(): ?string
    {
        return $this->_get(self::REGISTERED);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\UserInterface::setOrganisations()
     */
    public function setOrganisations(?array $organisations): UserInterface
    {
        return $this->setData(self::ORGANISATIONS, $organisations);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\UserInterface::getOrganisations()
     */
    public function getOrganisations(): ?array
    {
        return $this->_get(self::ORGANISATIONS);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\UserInterface::setType()
     */
    public function setType(?string $type): UserInterface
    {
        return $this->setData(self::TYPE, $type);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\UserInterface::getType()
     */
    public function getType(): ?string
    {
        return $this->_get(self::TYPE);
    }
}
