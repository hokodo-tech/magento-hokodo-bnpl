<?php
declare(strict_types=1);

namespace Hokodo\BNPL\Gateway\Service;

use Hokodo\BNPL\Api\Data\Gateway\CreateUserRequestInterface;
use Magento\Framework\Exception\NotFoundException;
use Magento\Payment\Gateway\Command\CommandException;

class User extends AbstractService
{
    /**
     * @throws NotFoundException
     * @throws CommandException
     */
    public function createUser(CreateUserRequestInterface $createUserRequest)
    {
        return $this->executeCommand('sdk_user_create', $createUserRequest);
    }
}
