<?php

declare(strict_types=1);

/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Controller\Adminhtml\Customer;

use Hokodo\BNPL\Api\Data\OrganisationInterface;
use Hokodo\BNPL\Api\Data\UserInterface;
use Hokodo\BNPL\Api\HokodoCustomerRepositoryInterface;
use Hokodo\BNPL\Gateway\Service\Organisation;
use Hokodo\BNPL\Gateway\Service\User;
use Hokodo\BNPL\Model\RequestBuilder\OrganisationBuilder;
use Hokodo\BNPL\Model\RequestBuilder\UserBuilder;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Controller\ResultInterface;
use Psr\Log\LoggerInterface;

class SaveCompanyId extends Action implements HttpPostActionInterface
{
    /**
     * @var JsonFactory
     */
    private JsonFactory $resultJsonFactory;

    /**
     * @var CustomerRepositoryInterface
     */
    private CustomerRepositoryInterface $customerRepository;

    /**
     * @var HokodoCustomerRepositoryInterface
     */
    private HokodoCustomerRepositoryInterface $hokodoCustomerRepository;

    /**
     * @var Organisation
     */
    private Organisation $organisationService;

    /**
     * @var User
     */
    private User $userService;

    /**
     * @var OrganisationBuilder
     */
    private OrganisationBuilder $organisationBuilder;

    /**
     * @var UserBuilder
     */
    private UserBuilder $userBuilder;

    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * @param Context                           $context
     * @param JsonFactory                       $resultJsonFactory
     * @param CustomerRepositoryInterface       $customerRepository
     * @param HokodoCustomerRepositoryInterface $hokodoCustomerRepository
     * @param Organisation                      $organisationService
     * @param User                              $userService
     * @param OrganisationBuilder               $organisationBuilder
     * @param UserBuilder                       $userBuilder
     * @param LoggerInterface                   $logger
     */
    public function __construct(
        Context $context,
        JsonFactory $resultJsonFactory,
        CustomerRepositoryInterface $customerRepository,
        HokodoCustomerRepositoryInterface $hokodoCustomerRepository,
        Organisation $organisationService,
        User $userService,
        OrganisationBuilder $organisationBuilder,
        UserBuilder $userBuilder,
        LoggerInterface $logger
    ) {
        parent::__construct($context);
        $this->resultJsonFactory = $resultJsonFactory;
        $this->customerRepository = $customerRepository;
        $this->hokodoCustomerRepository = $hokodoCustomerRepository;
        $this->organisationService = $organisationService;
        $this->userService = $userService;
        $this->organisationBuilder = $organisationBuilder;
        $this->userBuilder = $userBuilder;
        $this->logger = $logger;
    }

    /**
     * Save Company Id and Create Organization Id and User Id if needed.
     *
     * @return ResultInterface
     */
    public function execute(): ResultInterface
    {
        $resultJson = $this->resultJsonFactory->create();
        $result = [
            'success' => false,
            'message' => __('Company Was Not Updated.'),
        ];
        $customerId = $this->getRequest()->getParam('customerId');
        $companyId = $this->getRequest()->getParam('companyId');

        if (empty($companyId) || empty($customerId)) {
            return $resultJson->setData($result);
        }

        $hokodoCustomer = $this->hokodoCustomerRepository->getByCustomerId((int) $customerId);
        $hokodoCustomer->setCompanyId($companyId);

        if (!$hokodoCustomer || !$hokodoCustomer->getId()) {
            $hokodoCustomer
                ->setCustomerId((int) $customerId)
                ->setOrganisationId('')
                ->setUserId('');
        }

        try {
            /* @todo remove this and similar piec of code from webapi into on service */
            if (!$hokodoCustomer->getOrganisationId()) {
                /** @var CustomerRepositoryInterface $customer */
                $customer = $this->customerRepository->getById($customerId);
                /** @var OrganisationInterface $organisation */
                $organisation = $this->organisationService->createOrganisation(
                    $this->organisationBuilder->build(
                        $companyId,
                        $customer->getEmail()
                    )
                )->getDataModel();
                $hokodoCustomer->setOrganisationId($organisation->getId());
            }

            if (!$hokodoCustomer->getUserId()) {
                if (empty($customer)) {
                    $customer = $this->customerRepository->getById($customerId);
                }
                /** @var UserInterface $user */
                $user = $this->userService->createUser(
                    $this->userBuilder->build($customer, $organisation->getId())
                )->getDataModel();
                $hokodoCustomer->setUserId($user->getId());
            }
            $this->hokodoCustomerRepository->save($hokodoCustomer);
            $result = [
                'success' => true,
                'message' => __('Company Was Updated.'),
            ];
            return $resultJson->setData($result);
        } catch (\Exception $e) {
            $this->logger->critical(
                __('Hokodo_BNPL: set company to user failed with error - %1', $e->getMessage())
            );
            return $resultJson->setData($result);
        }
    }
}
