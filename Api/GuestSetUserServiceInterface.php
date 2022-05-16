<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Api;

/**
 * Interface Hokodo\BNPL\Api\GuestSetUserServiceInterface.
 */
interface GuestSetUserServiceInterface
{
    /**
     * A function that set user.
     *
     * @param string                              $cartId
     * @param \Hokodo\BNPL\Api\Data\UserInterface $user
     *
     * @return \Hokodo\BNPL\Api\Data\UserOrganisationResultInterface
     */
    public function setUser($cartId, Data\UserInterface $user);
}
