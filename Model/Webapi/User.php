<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Hokodo\BNPL\Model\Webapi;

use Hokodo\BNPL\Api\Data\Gateway\CreateUserRequestInterface as CreateUserGatewayRequest;
use Hokodo\BNPL\Api\Data\Gateway\CreateUserRequestInterfaceFactory;
use Hokodo\BNPL\Api\Data\Gateway\UserOrganisationInterface;
use Hokodo\BNPL\Api\Data\Gateway\UserOrganisationInterfaceFactory;
use Hokodo\BNPL\Api\Data\Webapi\CreateUserRequestInterface;
use Hokodo\BNPL\Api\Data\Webapi\CreateUserResponseInterface;
use Hokodo\BNPL\Api\Data\Webapi\CreateUserResponseInterfaceFactory;
use Hokodo\BNPL\Api\HokodoQuoteRepositoryInterface;
use Hokodo\BNPL\Api\Webapi\UserInterface;
use Hokodo\BNPL\Gateway\Service\User as UserService;
use Magento\Checkout\Model\Session;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\Webapi\Exception;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;

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
     * @var Session
     */
    private Session $checkoutSession;

    /**
     * @var HokodoQuoteRepositoryInterface
     */
    private HokodoQuoteRepositoryInterface $hokodoQuoteRepository;

    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * User constructor.
     *
     * @param UserService                        $userService
     * @param CustomerRepositoryInterface        $customerRepository
     * @param StoreManagerInterface              $storeManager
     * @param CreateUserRequestInterfaceFactory  $createUserGatewayRequestFactory
     * @param CreateUserResponseInterfaceFactory $createUserResponseFactory
     * @param UserOrganisationInterfaceFactory   $userOrganisationInterfaceFactory
     * @param HokodoQuoteRepositoryInterface     $hokodoQuoteRepository
     * @param Session                            $checkoutSession
     * @param LoggerInterface                    $logger
     */
    public function __construct(
        UserService $userService,
        CustomerRepositoryInterface $customerRepository,
        StoreManagerInterface $storeManager,
        CreateUserRequestInterfaceFactory $createUserGatewayRequestFactory,
        CreateUserResponseInterfaceFactory $createUserResponseFactory,
        UserOrganisationInterfaceFactory $userOrganisationInterfaceFactory,
        HokodoQuoteRepositoryInterface $hokodoQuoteRepository,
        Session $checkoutSession,
        LoggerInterface $logger
    ) {
        $this->userService = $userService;
        $this->createUserGatewayRequestFactory = $createUserGatewayRequestFactory;
        $this->storeManager = $storeManager;
        $this->createUserResponseFactory = $createUserResponseFactory;
        $this->customerRepository = $customerRepository;
        $this->userOrganisationInterfaceFactory = $userOrganisationInterfaceFactory;
        $this->checkoutSession = $checkoutSession;
        $this->hokodoQuoteRepository = $hokodoQuoteRepository;
        $this->logger = $logger;
    }

    /**
     * User create request webapi handler.
     *
     * @param CreateUserRequestInterface $payload
     *
     * @return CreateUserResponseInterface
     *
     * @throws Exception
     */
    public function create(CreateUserRequestInterface $payload): CreateUserResponseInterface
    {
        $result = $this->createUserResponseFactory->create();
        $customer = null;
        try {
            $customer = $this->customerRepository->get($payload->getEmail(), $this->storeManager->getStore()->getId());
        } catch (\Exception $e) {
            $this->logger->error(__('Hokodo_BNPL: createUser call failed with error - %1', $e->getMessage()));
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
                $hokodoQuote = $this->hokodoQuoteRepository->getByQuoteId($this->checkoutSession->getQuoteId());
                if (!$hokodoQuote->getQuoteId()) {
                    $hokodoQuote->setQuoteId((int) $this->checkoutSession->getQuoteId());
                }
                $hokodoQuote->setUserId($dataModel->getId());
                $this->hokodoQuoteRepository->save($hokodoQuote);
                $result->setId($dataModel->getId());
            } else {
                $result->setId('');
            }
        } catch (\Exception $e) {
            $this->logger->error(__('Hokodo_BNPL: createUser call failed with error - %1', $e->getMessage()));
            throw new Exception(
                __('There was an error during payment method set up. Please reload the page or try again later.')
            );
        }
        return $result;
    }
}
