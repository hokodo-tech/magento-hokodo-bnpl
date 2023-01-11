<?php
/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Hokodo\BNPL\Gateway\Service;

use Hokodo\BNPL\Api\Data\Gateway\CreateUserRequestInterface;
use Magento\Framework\Exception\NotFoundException;
use Magento\Payment\Gateway\Command\CommandException;
use Magento\Payment\Gateway\Command\ResultInterface;

class User extends AbstractService
{
    /**
     * Create user service command.
     *
     * @param CreateUserRequestInterface $createUserRequest
     *
     * @return ResultInterface|null
     *
     * @throws CommandException
     * @throws NotFoundException
     */
    public function createUser(CreateUserRequestInterface $createUserRequest): ?ResultInterface
    {
        return $this->executeCommand('sdk_user_create', $createUserRequest);
    }
}
