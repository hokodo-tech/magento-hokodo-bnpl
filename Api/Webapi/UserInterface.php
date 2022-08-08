<?php

namespace Hokodo\BNPL\Api\Webapi;

use Hokodo\BNPL\Api\Data\Webapi\CreateUserRequestInterface;
use Hokodo\BNPL\Api\Data\Webapi\CreateUserResponseInterface;

interface UserInterface
{
    /**
     * @param CreateUserRequestInterface $payload
     *
     * @return CreateUserResponseInterface
     */
    public function create(CreateUserRequestInterface $payload): CreateUserResponseInterface;
}
