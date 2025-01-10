<?php

/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Hokodo\BNPL\Gateway\Service;

use Hokodo\BNPL\Api\Data\Gateway\CreateUserRequestInterface;
use Hokodo\BNPL\Gateway\Command\Result\UserResultInterface;
use Magento\Framework\Exception\NotFoundException;
use Magento\Payment\Gateway\Command\CommandException;

class User extends AbstractService
{
    /**
     * Create user service command.
     *
     * @param CreateUserRequestInterface $createUserRequest
     *
     * @return UserResultInterface|null
     *
     * @throws CommandException
     * @throws NotFoundException
     */
    public function createUser(CreateUserRequestInterface $createUserRequest)
    {
        return $this->executeCommand('user_create', $createUserRequest);
    }
}
