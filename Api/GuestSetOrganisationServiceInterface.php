<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Api;

/**
 * Interface Hokodo\BNPL\Api\GuestSetOrganisationServiceInterface.
 */
interface GuestSetOrganisationServiceInterface
{
    /**
     * A function that set organisation.
     *
     * @param string                                            $cartId
     * @param \Hokodo\BNPL\Api\Data\HokodoOrganisationInterface $organisation
     * @param \Hokodo\BNPL\Api\Data\UserInterface               $user
     *
     * @return \Hokodo\BNPL\Api\Data\UserOrganisationResultInterface
     */
    public function setOrganisation(
        $cartId,
        Data\HokodoOrganisationInterface $organisation,
        Data\UserInterface $user
    );
}
