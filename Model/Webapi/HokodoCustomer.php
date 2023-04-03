<?php
/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Hokodo\BNPL\Model\Webapi;

use Hokodo\BNPL\Api\Data\OrganisationInterface;
use Hokodo\BNPL\Api\Data\UserInterface;
use Hokodo\BNPL\Api\Data\Webapi\HokodoCustomerRequestInterface;
use Hokodo\BNPL\Api\Data\Webapi\HokodoCustomerResponseInterface;
use Hokodo\BNPL\Api\Data\Webapi\HokodoCustomerResponseInterfaceFactory;
use Hokodo\BNPL\Api\HokodoCustomerRepositoryInterface;
use Hokodo\BNPL\Api\Webapi\HokodoCustomerInterface;
use Hokodo\BNPL\Gateway\Service\Organisation;
use Hokodo\BNPL\Gateway\Service\User;
use Hokodo\BNPL\Model\CompanyCreditService;
use Hokodo\BNPL\Model\HokodoCompanyProvider;
use Hokodo\BNPL\Model\RequestBuilder\OrganisationBuilder;
use Hokodo\BNPL\Model\RequestBuilder\UserBuilder;
use Magento\Customer\Model\Session;
use Magento\Framework\Exception\RuntimeException;
use Psr\Log\LoggerInterface;

class HokodoCustomer implements HokodoCustomerInterface
{
    /**
     * @var HokodoCustomerResponseInterfaceFactory
     */
    private HokodoCustomerResponseInterfaceFactory $hokodoCustomerResponseFactory;

    /**
     * @var Session
     */
    private Session $customerSession;

    /**
     * @var HokodoCustomerRepositoryInterface
     */
    private HokodoCustomerRepositoryInterface $hokodoCustomerRepository;

    /**
     * @var OrganisationBuilder
     */
    private OrganisationBuilder $organisationBuilder;

    /**
     * @var UserBuilder
     */
    private UserBuilder $userBuilder;

    /**
     * @var Organisation
     */
    private Organisation $organisationService;

    /**
     * @var User
     */
    private User $userService;

    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * @var HokodoCompanyProvider
     */
    private HokodoCompanyProvider $hokodoCompanyProvider;

    /**
     * @var \Hokodo\BNPL\Model\CompanyCreditService
     */
    private CompanyCreditService $companyCreditService;

    /**
     * @param HokodoCustomerResponseInterfaceFactory  $hokodoCustomerResponseFactory
     * @param Session                                 $customerSession
     * @param HokodoCustomerRepositoryInterface       $hokodoCustomerRepository
     * @param HokodoCompanyProvider                   $hokodoCompanyProvider
     * @param OrganisationBuilder                     $organisationBuilder
     * @param UserBuilder                             $userBuilder
     * @param Organisation                            $organisationService
     * @param User                                    $userService
     * @param LoggerInterface                         $logger
     * @param \Hokodo\BNPL\Model\CompanyCreditService $companyCreditService
     */
    public function __construct(
        HokodoCustomerResponseInterfaceFactory $hokodoCustomerResponseFactory,
        Session $customerSession,
        HokodoCustomerRepositoryInterface $hokodoCustomerRepository,
        HokodoCompanyProvider $hokodoCompanyProvider,
        OrganisationBuilder $organisationBuilder,
        UserBuilder $userBuilder,
        Organisation $organisationService,
        User $userService,
        LoggerInterface $logger,
        CompanyCreditService $companyCreditService
    ) {
        $this->hokodoCustomerResponseFactory = $hokodoCustomerResponseFactory;
        $this->customerSession = $customerSession;
        $this->hokodoCustomerRepository = $hokodoCustomerRepository;
        $this->hokodoCompanyProvider = $hokodoCompanyProvider;
        $this->organisationBuilder = $organisationBuilder;
        $this->userBuilder = $userBuilder;
        $this->organisationService = $organisationService;
        $this->userService = $userService;
        $this->logger = $logger;
        $this->companyCreditService = $companyCreditService;
    }

    /**
     * Assign Hokodo(companyId, organisationId, userId) to a magento customer.
     *
     * @param HokodoCustomerRequestInterface $payload
     *
     * @return HokodoCustomerResponseInterface
     *
     * @throws RuntimeException
     */
    public function assignCompany(HokodoCustomerRequestInterface $payload): HokodoCustomerResponseInterface
    {
        $hokodoCustomerResponse = $this->hokodoCustomerResponseFactory->create();
        $hokodoCustomer = $this->getHokodoCustomer($payload);
        if ($hokodoCustomer->getCompanyId() !== $payload->getCompanyId()) {
            $hokodoCustomer
                ->setCompanyId($payload->getCompanyId())
                ->setCreditLimit($this->companyCreditService->getCreditLimit($payload->getCompanyId()))
                ->setOrganisationId('')
                ->setUserId('');
        }

        try {
            if (!$hokodoCustomer->getOrganisationId()) {
                /** @var OrganisationInterface $organisation */
                $organisation = $this->organisationService->createOrganisation(
                    $this->organisationBuilder->build(
                        $payload->getCompanyId(),
                        $this->customerSession->getCustomer()->getEmail()
                    )
                )->getDataModel();
                $hokodoCustomer->setOrganisationId($organisation->getId());
            }

            if (!$hokodoCustomer->getUserId()) {
                /** @var UserInterface $user */
                $user = $this->userService->createUser(
                    $this->userBuilder->build($this->customerSession->getCustomer(), $organisation->getId())
                )->getDataModel();
                $hokodoCustomer->setUserId($user->getId());
                $this->hokodoCustomerRepository->save($hokodoCustomer);
            }

            $hokodoCustomerResponse
                ->setOrganisationId($hokodoCustomer->getOrganisationId())
                ->setUserId($hokodoCustomer->getUserId());

            return $hokodoCustomerResponse;
        } catch (\Exception $e) {
            $data = [
                'message' => 'Hokodo_BNPL: set company to user failed with error',
                'error' => $e->getMessage(),
            ];
            $this->logger->critical(__METHOD__, $data);
        }

        throw new RuntimeException(__('There was an error. Please try again.'));
    }

    /**
     * Get Hokodo user from repository.
     *
     * @param HokodoCustomerRequestInterface $payload
     *
     * @return \Hokodo\BNPL\Api\Data\HokodoCustomerInterface
     */
    private function getHokodoCustomer(HokodoCustomerRequestInterface $payload)
    {
        $customerId = (int) $payload->getCustomerId() ?: (int) $this->customerSession->getCustomer()->getId();
        $hokodoCustomer = $this->hokodoCustomerRepository->getByCustomerId($customerId);
        if (!$hokodoCustomer->getCustomerId()) {
            $hokodoCustomer->setCustomerId($customerId);
        }

        return $hokodoCustomer;
    }
}
