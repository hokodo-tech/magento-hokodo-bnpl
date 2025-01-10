<?php

/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Hokodo\BNPL\Test\Integration\Gateway\Service;

use Hokodo\BNPL\Api\Data\Gateway\CreateUserRequestInterface;
use Hokodo\BNPL\Gateway\Service\User;

class UserTest extends AbstractService
{
    /**
     * @var array
     */
    protected $httpResponse = [
        'id' => 'user-VG4i93EPLRamVFK6oXpvt9',
        'email' => 'johnny@hokodo.co',
        'unique_id' => '',
        'name' => 'John',
        'phone' => '',
        'registered' => '2017-06-01T14:37:12Z',
        'organisations' => [
            [
                'id' => 'org-FEjrdMsmhdNp34HxaZ6n63',
                'role' => 'member',
            ],
        ],
    ];

    /**
     * @return void
     *
     * @throws \Magento\Framework\Exception\NotFoundException
     * @throws \Magento\Payment\Gateway\Command\CommandException
     */
    public function testCreate()
    {
        $userService = $this->objectManager->get(User::class);
        $createUserRequest = $this->objectManager->get(CreateUserRequestInterface::class);

        $result = $userService->createUser($createUserRequest);

        $user = $result->getDataModel();

        $this->assertInstanceOf(\Hokodo\BNPL\Api\Data\UserInterface::class, $user);
        $this->assertEquals($this->httpResponse['id'], $user->getId());
    }
}
