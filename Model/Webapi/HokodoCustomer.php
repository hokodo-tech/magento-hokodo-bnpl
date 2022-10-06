<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
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
     * @param HokodoCustomerResponseInterfaceFactory $hokodoCustomerResponseFactory
     * @param Session                                $customerSession
     * @param HokodoCustomerRepositoryInterface      $hokodoCustomerRepository
     * @param OrganisationBuilder                    $organisationBuilder
     * @param UserBuilder                            $userBuilder
     * @param Organisation                           $organisationService
     * @param User                                   $userService
     * @param LoggerInterface                        $logger
     */
    public function __construct(
        HokodoCustomerResponseInterfaceFactory $hokodoCustomerResponseFactory,
        Session $customerSession,
        HokodoCustomerRepositoryInterface $hokodoCustomerRepository,
        OrganisationBuilder $organisationBuilder,
        UserBuilder $userBuilder,
        Organisation $organisationService,
        User $userService,
        LoggerInterface $logger
    ) {
        $this->hokodoCustomerResponseFactory = $hokodoCustomerResponseFactory;
        $this->customerSession = $customerSession;
        $this->hokodoCustomerRepository = $hokodoCustomerRepository;
        $this->organisationBuilder = $organisationBuilder;
        $this->userBuilder = $userBuilder;
        $this->organisationService = $organisationService;
        $this->userService = $userService;
        $this->logger = $logger;
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
                ->setOrganisationId('')
                ->setUserId('');
        }

        try {
            if (!$hokodoCustomer->getOrganisationId()) {
                /** @var OrganisationInterface $organisation */
                $organisation = $this->organisationService->createOrganisation(
                    $this->organisationBuilder->build($payload->getCompanyId())
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
            $this->logger->critical(__('Hokodo_BNPL: set company to user failed with error - %1', $e->getMessage()));
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