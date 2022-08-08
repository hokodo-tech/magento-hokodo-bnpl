<?php

namespace Hokodo\BNPL\Api\Data\Webapi;

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
