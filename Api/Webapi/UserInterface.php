<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

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
