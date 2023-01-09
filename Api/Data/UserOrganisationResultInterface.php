<?php
/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Api\Data;

/**
 * Interface Hokodo\BNPL\Api\Data\UserOrganisationResultInterface.
 */
interface UserOrganisationResultInterface
{
    public const USER = 'user';
    public const ORGANISATION = 'organisation';

    /**
     * A function that sets user.
     *
     * @param \Hokodo\BNPL\Api\Data\UserInterface $user
     *
     * @return $this
     */
    public function setUser(UserInterface $user);

    /**
     * A function that gets user.
     *
     * @return \Hokodo\BNPL\Api\Data\UserInterface
     */
    public function getUser();

    /**
     * A function that sets organisation.
     *
     * @param \Hokodo\BNPL\Api\Data\HokodoOrganisationInterface $organisation
     *
     * @return $this
     */
    public function setOrganisation(HokodoOrganisationInterface $organisation);

    /**
     * A function that gets organisation.
     *
     * @return \Hokodo\BNPL\Api\Data\HokodoOrganisationInterface
     */
    public function getOrganisation();
}
