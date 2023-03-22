<?php
/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Hokodo\BNPL\Api\Data;

/**
 * Interface Hokodo\BNPL\Api\Data\OrganisationInterface.
 */
interface OrganisationInterface
{
    /**#@+
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    public const ID = 'id';
    public const UNIQUE_ID = 'unique_id';
    public const REGISTERED = 'registered';
    public const COMPANY = 'company';
    public const USERS = 'users';

    /**
     * A function that sets id.
     *
     * @param string $id
     *
     * @return $this
     */
    public function setId(string $id): self;

    /**
     * A function that gets id.
     *
     * @return string
     */
    public function getId(): string;

    /**
     * A function that sets unique id.
     *
     * @param string $uniqueId
     *
     * @return $this
     */
    public function setUniqueId(string $uniqueId): self;

    /**
     * A function that gets unique id.
     *
     * @return string
     */
    public function getUniqueId(): string;

    /**
     * A function that sets registered.
     *
     * @param string $registered
     *
     * @return $this
     */
    public function setRegistered(string $registered): self;

    /**
     * A function that gets registered.
     *
     * @return string
     */
    public function getRegistered(): string;

    /**
     * A function that sets company.
     *
     * @param string $company
     *
     * @return $this
     */
    public function setCompany(string $company): self;

    /**
     * A function that gets company.
     *
     * @return string
     */
    public function getCompany(): string;

    /**
     * A function that sets users.
     *
     * @param \Hokodo\BNPL\Api\Data\OrganisationUserInterface[] $users
     *
     * @return $this
     */
    public function setUsers(array $users): self;

    /**
     * A function that gets users.
     *
     * @return \Hokodo\BNPL\Api\Data\OrganisationUserInterface[]
     */
    public function getUsers(): array;
}
