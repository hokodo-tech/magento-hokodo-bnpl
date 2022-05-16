<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Api;

/**
 * Interface Hokodo\BNPL\Api\SetOrganisationServiceInterface.
 */
interface SetOrganisationServiceInterface
{
    /**
     * A function that sets organisations.
     *
     * @param int                                               $cartId
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
