<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Hokodo\BNPL\Api\Data\Webapi;

interface CreateUserRequestInterface
{
    public const NAME = 'name';
    public const EMAIL = 'email';
    public const ORGANISATION_ID = 'organisation_id';

    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName(string $name): self;

    /**
     * @return string
     */
    public function getEmail(): string;

    /**
     * @param string $email
     *
     * @return $this
     */
    public function setEmail(string $email): self;

    /**
     * @return string
     */
    public function getOrganisationId(): string;

    /**
     * @param string $organisationId
     *
     * @return $this
     */
    public function setOrganisationId(string $organisationId): self;
}
