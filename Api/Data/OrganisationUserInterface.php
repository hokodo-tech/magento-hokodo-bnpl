<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Api\Data;

/**
 * Interface Hokodo\BNPL\Api\Data\OrganisationUserInterface.
 */
interface OrganisationUserInterface
{
    public const ID = 'id';
    public const EMAIL = 'email';
    public const ROLE = 'role';
    public const MEMBER = 'member';

    /**
     * Set API unique identifier for the user.
     *
     * @param string $id
     *
     * @return $this
     */
    public function setId($id);

    /**
     * Get  API unique identifier for the user.
     *
     * @return string
     */
    public function getId();

    /**
     * Set Email of the user.
     *
     * @param string $email
     *
     * @return $this
     */
    public function setEmail($email);

    /**
     * Get Email of the user.
     *
     * @return string
     */
    public function getEmail();

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
