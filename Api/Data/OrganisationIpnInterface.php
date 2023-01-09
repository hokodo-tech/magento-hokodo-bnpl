<?php
/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Api\Data;

/**
 * Interface Hokodo\BNPL\Api\Data\OrganisationIpnInterface.
 */
interface OrganisationIpnInterface extends \Magento\Framework\Api\ExtensibleDataInterface
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
    public function setId($id);

    /**
     * A function that gets id.
     *
     * @return string
     */
    public function getId();

    /**
     * A function that sets unique id.
     *
     * @param string $uniqueId
     *
     * @return $this
     */
    public function setUniqueId($uniqueId);

    /**
     * A function that gets unique id.
     *
     * @return string
     */
    public function getUniqueId();

    /**
     * A function that set registered.
     *
     * @param string $registered
     *
     * @return $this
     */
    public function setRegistered($registered);

    /**
     * A function that gets registered.
     *
     * @return string
     */
    public function getRegistered();

    /**
     *  A function that sets company.
     *
     * @param \Hokodo\BNPL\Api\Data\CompanyInterface $company
     *
     * @return $this
     */
    public function setCompany(CompanyInterface $company);

    /**
     * A function that gets company.
     *
     * @return Hokodo\BNPL\Api\Data\CompanyInterface
     */
    public function getCompany();

    /**
     * A function that set users.
     *
     * @param string[] $users
     *
     * @return $this
     */
    public function setUsers(array $users);

    /**
     * A function that gets users.
     *
     * @return string[]
     */
    public function getUsers();
}
