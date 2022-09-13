<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Hokodo\BNPL\Model\RequestBuilder;

use Hokodo\BNPL\Api\Data\Gateway\CreateUserRequestInterface;
use Hokodo\BNPL\Api\Data\Gateway\CreateUserRequestInterfaceFactory;
use Hokodo\BNPL\Api\Data\Gateway\UserOrganisationInterface;
use Hokodo\BNPL\Api\Data\Gateway\UserOrganisationInterfaceFactory;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;

class UserBuilder
{
    /**
     * @var CreateUserRequestInterfaceFactory
     */
    private CreateUserRequestInterfaceFactory $createUserGatewayRequestFactory;

    /**
     * @var StoreManagerInterface
     */
    private StoreManagerInterface $storeManager;

    /**
     * @var CustomerRepositoryInterface
     */
    private CustomerRepositoryInterface $customerRepository;

    /**
     * @var UserOrganisationInterfaceFactory
     */
    private UserOrganisationInterfaceFactory $userOrganisationInterfaceFactory;

    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * @param CreateUserRequestInterfaceFactory $createUserGatewayRequestFactory
     * @param StoreManagerInterface             $storeManager
     * @param CustomerRepositoryInterface       $customerRepository
     * @param UserOrganisationInterfaceFactory  $userOrganisationInterfaceFactory
     * @param LoggerInterface                   $logger
     */
    public function __construct(
        CreateUserRequestInterfaceFactory $createUserGatewayRequestFactory,
        StoreManagerInterface $storeManager,
        CustomerRepositoryInterface $customerRepository,
        UserOrganisationInterfaceFactory $userOrganisationInterfaceFactory,
        LoggerInterface $logger
    ) {
        $this->createUserGatewayRequestFactory = $createUserGatewayRequestFactory;
        $this->storeManager = $storeManager;
        $this->customerRepository = $customerRepository;
        $this->userOrganisationInterfaceFactory = $userOrganisationInterfaceFactory;
        $this->logger = $logger;
    }

    /**
     * User request object builder.
     *
     * @param string $email
     * @param string $name
     * @param string $organisationId
     *
     * @return CreateUserRequestInterface
     */
    public function build(string $email, string $name, string $organisationId): CreateUserRequestInterface
    {
        $customer = null;
        try {
            $customer = $this->customerRepository->get($email, $this->storeManager->getStore()->getId());
        } catch (\Exception $e) {
            $this->logger->error(__('Hokodo_BNPL: createUser call failed with error - %1', $e->getMessage()));
        }

        $gatewayRequest = $this->createUserGatewayRequestFactory->create();
        /* @var $gatewayRequest CreateUserRequestInterface */
        $gatewayRequest
            ->setEmail($email)
            ->setName($name)
            ->setRegistered($customer ? $customer->getCreatedAt() : date('Y-m-d\TH:i:s\Z'));

        $organisation = $this->userOrganisationInterfaceFactory->create();
        /* @var $organisation UserOrganisationInterface */
        $organisation->setId($organisationId)->setRole('member');
        $gatewayRequest->setOrganisations([$organisation]);

        return $gatewayRequest;
    }
}
