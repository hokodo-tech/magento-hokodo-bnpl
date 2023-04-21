<?php
/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Hokodo\BNPL\Api\Data;

/**
 * Interface Hokodo\BNPL\Api\Data\UserInterface.
 */
interface UserInterface extends \Magento\Framework\Api\ExtensibleDataInterface
{
    public const ID = 'id';
    public const EMAIL = 'email';
    public const EMAIL_VALIDATED = 'email_validated';
    public const UNIQUE_ID = 'unique_id';
    public const NAME = 'name';
    public const PHONE = 'phone';
    public const REGISTERED = 'registered';
    public const ORGANISATIONS = 'organisations';
    public const TYPE = 'type';

    /**
     * A function that sets id.
     *
     * @param string|null $id
     *
     * @return $this
     */
    public function setId(?string $id): self;

    /**
     * A function that id.
     *
     * @return string|null
     */
    public function getId(): ?string;

    /**
     * A function that sets email.
     *
     * @param string|null $email
     *
     * @return $this
     */
    public function setEmail(?string $email): self;

    /**
     * A function that gets email.
     *
     * @return string|null
     */
    public function getEmail(): ?string;

    /**
     * A function that sets email validated.
     *
     * @param bool|null $emailValidated
     *
     * @return $this
     */
    public function setEmailValidated(?bool $emailValidated): self;

    /**
     * A function that gets email validated.
     *
     * @return bool
     */
    public function getEmailValidated(): bool;

    /**
     * A function that sets unique id.
     *
     * @param string|null $uniqueId
     *
     * @return $this
     */
    public function setUniqueId(?string $uniqueId): self;

    /**
     * A function that gets unique id.
     *
     * @return string|null
     */
    public function getUniqueId(): ?string;

    /**
     * A function that sets name.
     *
     * @param string|null $name
     *
     * @return $this
     */
    public function setName(?string $name): self;

    /**
     * A function that gets name.
     *
     * @return string|null
     */
    public function getName(): ?string;

    /**
     * A function that sets phone.
     *
     * @param string|null $phone
     *
     * @return $this
     */
    public function setPhone(?string $phone): self;

    /**
     * A function that gets phone.
     *
     * @return string|null
     */
    public function getPhone(): ?string;

    /**
     * A function that sets registered.
     *
     * @param string|null $registered
     *
     * @return $this
     */
    public function setRegistered(?string $registered): self;

    /**
     * A function that gets registered.
     *
     * @return string|null
     */
    public function getRegistered(): ?string;

    /**
     * A function that sets organisations.
     *
     * @param \Hokodo\BNPL\Api\Data\UserOrganisationInterface[]|null $organisations
     *
     * @return $this
     */
    public function setOrganisations(?array $organisations): self;

    /**
     * A function that gets organisations.
     *
     * @return \Hokodo\BNPL\Api\Data\UserOrganisationInterface[]|null
     */
    public function getOrganisations(): ?array;

    /**
     * A function that sets type.
     *
     * @param string|null $type
     *
     * @return $this
     */
    public function setType(?string $type): self;

    /**
     * A function that gets type.
     *
     * @return string|null
     */
    public function getType(): ?string;
}
