<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Api;

/**
 * Interface Hokodo\BNPL\Api\SetUserServiceInterface.
 */
interface SetUserServiceInterface
{
    /**
     * A function that sets user interface.
     *
     * @param int                                 $cartId
     * @param \Hokodo\BNPL\Api\Data\UserInterface $user
     *
     * @return \Hokodo\BNPL\Api\Data\UserOrganisationResultInterface
     */
    public function setUser($cartId, Data\UserInterface $user);
}
