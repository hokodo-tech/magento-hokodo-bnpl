<?php

/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
// @codingStandardsIgnoreFile

declare(strict_types=1);

namespace Hokodo\BNPL\Api\Data\Gateway;

interface CreateUserRequestInterface
{
    public const NAME = 'name';
    public const EMAIL = 'email';
    public const REGISTERED = 'registered';
    public const ORGANISATIONS = 'organisations';

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName(string $name): self;

    /**
     * @param string $email
     *
     * @return $this
     */
    public function setEmail(string $email): self;

    /**
     * @param string $registered
     *
     * @return $this
     */
    public function setRegistered(string $registered): self;

    /**
     * @param \Hokodo\BNPL\Api\Data\UserOrganisationInterface[] $organisations
     *
     * @return $this
     */
    public function setOrganisations(array $organisations): self;
}
