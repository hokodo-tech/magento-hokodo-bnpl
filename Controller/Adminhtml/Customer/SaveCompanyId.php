<?php

declare(strict_types=1);

/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Controller\Adminhtml\Customer;

use Hokodo\BNPL\Api\CompanyCreditServiceInterface;
use Hokodo\BNPL\Api\Data\OrganisationInterface;
use Hokodo\BNPL\Api\HokodoCustomerRepositoryInterface;
use Hokodo\BNPL\Api\HokodoQuoteRepositoryInterface;
use Hokodo\BNPL\Gateway\Service\Organisation;
use Hokodo\BNPL\Gateway\Service\User;
use Hokodo\BNPL\Model\RequestBuilder\OrganisationBuilder;
use Hokodo\BNPL\Model\RequestBuilder\UserBuilder;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Api\SessionCleanerInterface;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Store\Model\App\Emulation;
use Psr\Log\LoggerInterface;

class SaveCompanyId extends Action implements HttpPostActionInterface
{
    /**
     * @var JsonFactory
     */
    private JsonFactory $resultJsonFactory;

    /**
     * @var Emulation
     */
    private $emulation;

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
     * @var CartRepositoryInterface
     */
    private CartRepositoryInterface $cartRepository;

    /**
     * @var HokodoQuoteRepositoryInterface
     */
    private HokodoQuoteRepositoryInterface $hokodoQuoteRepository;

    /**
     * @var SessionCleanerInterface
     */
    private SessionCleanerInterface $sessionCleanerInterface;

    /**
     * @var CompanyCreditServiceInterface
     */
    private CompanyCreditServiceInterface $companyCreditService;

    /**
     * @param Context                           $context
     * @param JsonFactory                       $resultJsonFactory
     * @param Emulation                         $emulation
     * @param CustomerRepositoryInterface       $customerRepository
     * @param HokodoCustomerRepositoryInterface $hokodoCustomerRepository
     * @param Organisation                      $organisationService
     * @param User                              $userService
     * @param OrganisationBuilder               $organisationBuilder
     * @param UserBuilder                       $userBuilder
     * @param LoggerInterface                   $logger
     * @param CartRepositoryInterface           $cartRepository
     * @param HokodoQuoteRepositoryInterface    $hokodoQuoteRepository
     * @param SessionCleanerInterface           $sessionCleanerInterface
     * @param CompanyCreditServiceInterface     $companyCreditService
     */
    public function __construct(
        Context $context,
        JsonFactory $resultJsonFactory,
        Emulation $emulation,
        CustomerRepositoryInterface $customerRepository,
        HokodoCustomerRepositoryInterface $hokodoCustomerRepository,
        Organisation $organisationService,
        User $userService,
        OrganisationBuilder $organisationBuilder,
        UserBuilder $userBuilder,
        LoggerInterface $logger,
        CartRepositoryInterface $cartRepository,
        HokodoQuoteRepositoryInterface $hokodoQuoteRepository,
        SessionCleanerInterface $sessionCleanerInterface,
        CompanyCreditServiceInterface $companyCreditService
    ) {
        parent::__construct($context);
        $this->resultJsonFactory = $resultJsonFactory;
        $this->emulation = $emulation;
        $this->customerRepository = $customerRepository;
        $this->hokodoCustomerRepository = $hokodoCustomerRepository;
        $this->organisationService = $organisationService;
        $this->userService = $userService;
        $this->organisationBuilder = $organisationBuilder;
        $this->userBuilder = $userBuilder;
        $this->logger = $logger;
        $this->cartRepository = $cartRepository;
        $this->hokodoQuoteRepository = $hokodoQuoteRepository;
        $this->sessionCleanerInterface = $sessionCleanerInterface;
        $this->companyCreditService = $companyCreditService;
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
            'message' => __('Error, the company has not been updated.'),
        ];
        $customerId = (int) $this->getRequest()->getParam('entityId');
        $companyId = $this->getRequest()->getParam('companyId');

        if (empty($companyId) || empty($customerId)) {
            return $resultJson->setData($result);
        }

        $hokodoCustomer = $this->hokodoCustomerRepository->getByCustomerId($customerId);
        if (!$hokodoCustomer->getId()) {
            $hokodoCustomer->setCustomerId($customerId);
        }
        $hokodoCustomer->setCompanyId($companyId);
        $hokodoCustomer->setCreditLimit($this->companyCreditService->getCreditLimit($companyId));

        try {
            /* @todo remove this and similar piec of code from webapi into on service */
            /** @var CustomerRepositoryInterface $customer */
            $customer = $this->customerRepository->getById($customerId);
            $this->emulation->startEnvironmentEmulation($customer->getStoreId());
            /** @var OrganisationInterface $organisation */
            $organisation = $this->organisationService->createOrganisation(
                $this->organisationBuilder->build(
                    $companyId,
                    $customer->getEmail()
                )
            )->getDataModel();

            $hokodoCustomer->setOrganisationId($organisation->getId());

            $user = $this->userService->createUser(
                $this->userBuilder->build($customer, $organisation->getId())
            )->getDataModel();
            $hokodoCustomer->setUserId($user->getId());

            $this->hokodoCustomerRepository->save($hokodoCustomer);
            $this->emulation->stopEnvironmentEmulation();
            try {
                $this->hokodoQuoteRepository->deleteByCustomerId($customerId);
            } catch (\Exception $exception) {
                $data = [
                    'message' => 'Hokodo_BNPL: try get active quote.',
                    'error' => $exception->getMessage(),
                ];
                $this->logger->notice(__METHOD__, $data);
            }
            $this->sessionCleanerInterface->clearFor($customerId);
            $result = [
                'success' => true,
                'message' => __('The company has been updated.'),
            ];
        } catch (\Exception $e) {
            $data = [
                'message' => 'Hokodo_BNPL: set company to user failed with error.',
                'error' => $e->getMessage(),
            ];
            $this->logger->critical(__METHOD__, $data);
        }
        return $resultJson->setData($result);
    }
}
