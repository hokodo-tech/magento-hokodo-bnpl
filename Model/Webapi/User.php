<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Hokodo\BNPL\Model\Webapi;

use Hokodo\BNPL\Api\Data\Gateway\CreateUserRequestInterface as CreateUserGatewayRequest;
use Hokodo\BNPL\Api\Data\Gateway\CreateUserRequestInterfaceFactory;
use Hokodo\BNPL\Api\Data\Webapi\CreateUserRequestInterface;
use Hokodo\BNPL\Api\Data\Webapi\CreateUserResponseInterface;
use Hokodo\BNPL\Api\Data\Webapi\CreateUserResponseInterfaceFactory;
use Hokodo\BNPL\Api\Data\Webapi\UserOrganisationInterface;
use Hokodo\BNPL\Api\Data\Webapi\UserOrganisationInterfaceFactory;
use Hokodo\BNPL\Api\Webapi\UserInterface;
use Hokodo\BNPL\Gateway\Service\User as UserService;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Store\Model\StoreManagerInterface;

class User implements UserInterface
{
    /**
     * @var UserService
     */
    private UserService $userService;

    /**
     * @var CreateUserRequestInterfaceFactory
     */
    private CreateUserRequestInterfaceFactory $createUserGatewayRequestFactory;

    /**
     * @var StoreManagerInterface
     */
    private StoreManagerInterface $storeManager;

    /**
     * @var CreateUserResponseInterfaceFactory
     */
    private CreateUserResponseInterfaceFactory $createUserResponseFactory;

    /**
     * @var CustomerRepositoryInterface
     */
    private CustomerRepositoryInterface $customerRepository;

    /**
     * @var UserOrganisationInterfaceFactory
     */
    private UserOrganisationInterfaceFactory $userOrganisationInterfaceFactory;

    /**
     * User constructor.
     *
     * @param UserService                        $userService
     * @param CustomerRepositoryInterface        $customerRepository
     * @param StoreManagerInterface              $storeManager
     * @param CreateUserRequestInterfaceFactory  $createUserGatewayRequestFactory
     * @param CreateUserResponseInterfaceFactory $createUserResponseFactory
     * @param UserOrganisationInterfaceFactory   $userOrganisationInterfaceFactory
     */
    public function __construct(
        UserService $userService,
        CustomerRepositoryInterface $customerRepository,
        StoreManagerInterface $storeManager,
        CreateUserRequestInterfaceFactory $createUserGatewayRequestFactory,
        CreateUserResponseInterfaceFactory $createUserResponseFactory,
        UserOrganisationInterfaceFactory $userOrganisationInterfaceFactory
    ) {
        $this->userService = $userService;
        $this->createUserGatewayRequestFactory = $createUserGatewayRequestFactory;
        $this->storeManager = $storeManager;
        $this->createUserResponseFactory = $createUserResponseFactory;
        $this->customerRepository = $customerRepository;
        $this->userOrganisationInterfaceFactory = $userOrganisationInterfaceFactory;
    }

    /**
     * User create request webapi handler.
     *
     * @param CreateUserRequestInterface $payload
     *
     * @return CreateUserResponseInterface
     */
    public function create(CreateUserRequestInterface $payload): CreateUserResponseInterface
    {
        //TODO create User Logic
        $result = $this->createUserResponseFactory->create();
        $customer = null;
        try {
            $customer = $this->customerRepository->get($payload->getEmail(), $this->storeManager->getStore()->getId());
        } catch (\Exception $e) {
            $customer = null;
        }
        try {
            $gatewayRequest = $this->createUserGatewayRequestFactory->create();
            /* @var $gatewayRequest CreateUserGatewayRequest */
            $gatewayRequest
                ->setEmail($payload->getEmail())
                ->setName($payload->getName())
                ->setRegistered($customer ? $customer->getCreatedAt() : date('Y-m-d\TH:i:s\Z'));
            $organisation = $this->userOrganisationInterfaceFactory->create();
            /* @var $organisation UserOrganisationInterface */
            $organisation->setId($payload->getOrganisationId())->setRole('member');
            $gatewayRequest->setOrganisations([$organisation]);
            $user = $this->userService->createUser($gatewayRequest);
            if ($dataModel = $user->getDataModel()) {
                $result->setId($dataModel->getId());
            }
        } catch (\Exception $e) {
            //TODO error reporting
            $result->setId('');
        }
        return $result;
    }
}
