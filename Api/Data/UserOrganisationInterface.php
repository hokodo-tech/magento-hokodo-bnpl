<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Api\Data;

/**
 * Interface Hokodo\BNPL\Api\Data\UserOrganisationInterface.
 */
interface UserOrganisationInterface
{
    public const ID = 'id';
    public const ROLE = 'role';

    /**
     * Set API unique identifier for the organisation.
     *
     * @param string $id
     *
     * @return $this
     */
    public function setId($id);

    /**
     * Get API unique identifier for the organisation.
     *
     * @return string
     */
    public function getId();

    /**
     * Set role of the user in the organisation.
     *
     * @param string $role
     *
     * @return $this
     */
    public function setRole($role);

    /**
     * Get role of the user in the organisation.
     *
     * @return string
     */
    public function getRole();
}
