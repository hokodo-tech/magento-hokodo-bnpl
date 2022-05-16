<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Service;

use Hokodo\BNPL\Api\Data\UserInterface;

/**
 * Class Hokodo\BNPL\Service\UserService.
 */
class UserService extends AbstractService
{
    /**
     * A function that create user interface.
     *
     * @param UserInterface $user
     *
     * @return UserInterface
     */
    public function create(UserInterface $user)
    {
        return $this->executeCommand('user_create', [
            'users' => $user,
        ])->getDataModel();
    }

    /**
     * A function that returns a list of users.
     *
     * @return UserInterface[]
     */
    public function getList()
    {
        return $this->executeCommand('user_list', [])->getList();
    }

    /**
     * A function that removes a user.
     *
     * @param UserInterface $user
     *
     * @return \Hokodo\BNPL\Gateway\Command\Result\Result
     */
    public function delete(UserInterface $user)
    {
        return $this->executeCommand('user_delete', [
            'users' => $user,
        ]);
    }
}
