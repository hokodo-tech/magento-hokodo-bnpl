<?php
/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
// @codingStandardsIgnoreFile

declare(strict_types=1);

namespace Hokodo\BNPL\Api\Data\Gateway;

interface UserOrganisationInterface
{
    public const ID = 'id';
    public const ROLE = 'role';

    /**
     * @param string $id
     *
     * @return $this
     */
    public function setId(string $id): self;

    /**
     * @param string $role
     *
     * @return $this
     */
    public function setRole(string $role): self;
}
