<?php

/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Hokodo\BNPL\Model\RequestBuilder;

use Hokodo\BNPL\Api\Data\Gateway\CreateUserRequestInterface;
use Hokodo\BNPL\Api\Data\Gateway\CreateUserRequestInterfaceFactory;
use Hokodo\BNPL\Api\Data\Gateway\UserOrganisationInterface;
use Hokodo\BNPL\Api\Data\Gateway\UserOrganisationInterfaceFactory;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Customer\Model\Customer;

class UserBuilder
{
    /**
     * @var CreateUserRequestInterfaceFactory
     */
    private CreateUserRequestInterfaceFactory $createUserGatewayRequestFactory;

    /**
     * @var UserOrganisationInterfaceFactory
     */
    private UserOrganisationInterfaceFactory $userOrganisationInterfaceFactory;

    /**
     * @param CreateUserRequestInterfaceFactory $createUserGatewayRequestFactory
     * @param UserOrganisationInterfaceFactory  $userOrganisationInterfaceFactory
     */
    public function __construct(
        CreateUserRequestInterfaceFactory $createUserGatewayRequestFactory,
        UserOrganisationInterfaceFactory $userOrganisationInterfaceFactory
    ) {
        $this->createUserGatewayRequestFactory = $createUserGatewayRequestFactory;
        $this->userOrganisationInterfaceFactory = $userOrganisationInterfaceFactory;
    }

    /**
     * User request object builder.
     *
     * @param CustomerInterface|Customer $customer
     * @param string                     $organisationId
     *
     * @return CreateUserRequestInterface
     */
    public function build($customer, string $organisationId): CreateUserRequestInterface
    {
        $gatewayRequest = $this->createUserGatewayRequestFactory->create();
        /* @var $gatewayRequest CreateUserRequestInterface */
        $gatewayRequest
            ->setEmail($customer->getEmail())
            ->setName($customer->getFirstname() . ' ' . $customer->getLastname())
            ->setRegistered($customer->getCreatedAt() ?: date('Y-m-d\TH:i:s\Z'));

        $organisation = $this->userOrganisationInterfaceFactory->create();
        /* @var $organisation UserOrganisationInterface */
        $organisation->setId($organisationId)->setRole('member');
        $gatewayRequest->setOrganisations([$organisation]);

        return $gatewayRequest;
    }
}
