<?php

/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Hokodo\BNPL\Observer\Customer;

use Hokodo\BNPL\Api\Data\CompanyInterface as ApiCompany;
use Hokodo\BNPL\Api\Data\Gateway\CompanySearchRequestInterfaceFactory;
use Hokodo\BNPL\Api\HokodoCustomerRepositoryInterface;
use Hokodo\BNPL\Gateway\Config\Config;
use Hokodo\BNPL\Gateway\Service\CompanySearch;
use Hokodo\BNPL\Gateway\Service\Organisation;
use Hokodo\BNPL\Gateway\Service\User;
use Hokodo\BNPL\Model\Config\Source\CustomerVatAttribute;
use Hokodo\BNPL\Model\RequestBuilder\OrganisationBuilder;
use Hokodo\BNPL\Model\RequestBuilder\UserBuilder;
use Magento\Customer\Model\Customer;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Psr\Log\LoggerInterface;

class AssignCompanyOnSave implements ObserverInterface
{
    /**
     * @var Config
     */
    private Config $config;

    /**
     * @var CompanySearchRequestInterfaceFactory
     */
    private CompanySearchRequestInterfaceFactory $companySearchRequestFactory;

    /**
     * @var CompanySearch
     */
    private CompanySearch $companySearch;

    /**
     * @var User
     */
    private User $user;

    /**
     * @var Organisation
     */
    private Organisation $organisation;

    /**
     * @var UserBuilder
     */
    private UserBuilder $userBuilder;

    /**
     * @var OrganisationBuilder
     */
    private OrganisationBuilder $organisationBuilder;

    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * @var HokodoCustomerRepositoryInterface
     */
    private HokodoCustomerRepositoryInterface $customerRepository;

    /**
     * @param Config                               $config
     * @param CompanySearchRequestInterfaceFactory $companySearchRequestFactory
     * @param CompanySearch                        $companySearch
     * @param User                                 $user
     * @param Organisation                         $organisation
     * @param UserBuilder                          $userBuilder
     * @param OrganisationBuilder                  $organisationBuilder
     * @param LoggerInterface                      $logger
     * @param HokodoCustomerRepositoryInterface    $customerRepository
     */
    public function __construct(
        Config $config,
        CompanySearchRequestInterfaceFactory $companySearchRequestFactory,
        CompanySearch $companySearch,
        User $user,
        Organisation $organisation,
        UserBuilder $userBuilder,
        OrganisationBuilder $organisationBuilder,
        LoggerInterface $logger,
        HokodoCustomerRepositoryInterface $customerRepository
    ) {
        $this->config = $config;
        $this->companySearchRequestFactory = $companySearchRequestFactory;
        $this->companySearch = $companySearch;
        $this->user = $user;
        $this->organisation = $organisation;
        $this->userBuilder = $userBuilder;
        $this->organisationBuilder = $organisationBuilder;
        $this->logger = $logger;
        $this->customerRepository = $customerRepository;
    }

    /**
     * @inheritDoc
     */
    public function execute(Observer $observer)
    {
        $customer = $observer->getData('customer');
        if (!($assignBasedOn = $this->config->getValue(
            Config::ASSIGN_COMPANY_BASED_ON,
            $customer->getStoreId()
        )) ||
            $assignBasedOn === CustomerVatAttribute::DISABLED) {
            return;
        }

        try {
            $hokodoCustomer = $this->customerRepository->getByCustomerId((int) $customer->getId());
            if ($hokodoCustomer->getCompanyId() || !($vatNumber = $this->getVatNumber($customer))) {
                return;
            }

            if (!($hokodoCompany = $this->searchHokodoCompany($vatNumber))) {
                return;
            }

            $hokodoOrganisation = $this->organisation->createOrganisation(
                $this->organisationBuilder->build(
                    $hokodoCompany->getId(),
                    $customer->getEmail()
                )
            )->getDataModel();

            $hokodoUser = $this->user->createUser(
                $this->userBuilder->build(
                    $customer,
                    $hokodoOrganisation->getId()
                ),
            )->getDataModel();

            $hokodoCustomer
                ->setCompanyId($hokodoCompany->getId())
                ->setOrganisationId($hokodoOrganisation->getId())
                ->setUserId($hokodoUser->getId())
                ->setCustomerId((int) $customer->getId());

            $this->customerRepository->save($hokodoCustomer);
        } catch (\Exception $e) {
            $this->logger->debug(
                __(
                    'There was an error while assigning Hokodo company to customer %s using vat number %s. Error: %s',
                    $customer->getId(),
                    $vatNumber,
                    $e->getMessage()
                )
            );
        }
    }

    /**
     * Get Vat number.
     *
     * @param Customer $customer
     *
     * @return string|null
     */
    private function getVatNumber(Customer $customer): ?string
    {
        $customerVatAttributeCode = $this->config->getValue(Config::VAT_ATTRIBUTE_CODE);
        if ($this->config->getValue(Config::ASSIGN_COMPANY_BASED_ON) === CustomerVatAttribute::CUSTOM &&
            $customerVatAttributeCode) {
            $vatNumber = $customer->getCustomAttribute($this->config->getValue($customerVatAttributeCode));
        } else {
            $vatNumber = $customerVatAttributeCode ?
                $customer->getData($customerVatAttributeCode) :
                $customer->getTaxvat();
        }

        return $vatNumber;
    }

    /**
     * Serach for Hokodo company.
     *
     * @param string $vatNumber
     *
     * @return ApiCompany[]
     *
     * @throws \Magento\Framework\Exception\NotFoundException
     * @throws \Magento\Payment\Gateway\Command\CommandException
     */
    public function searchHokodoCompany(string $vatNumber): ?ApiCompany
    {
        $hokodoCompanySearchRequest = $this->companySearchRequestFactory->create();
        $hokodoCompanySearchRequest
            ->setCountry($this->config->getValue(Config::ASSIGN_COMPANY_COUNTRY))
            ->setRegNumber($vatNumber);
        $searchResult = $this->companySearch->search($hokodoCompanySearchRequest)->getList();

        return reset($searchResult) ?: null;
    }
}
