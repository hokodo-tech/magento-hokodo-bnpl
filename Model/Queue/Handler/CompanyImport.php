<?php

/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Hokodo\BNPL\Model\Queue\Handler;

use Hokodo\BNPL\Api\CompanyCreditServiceInterface;
use Hokodo\BNPL\Api\Data\CompanyImportInterface;
use Hokodo\BNPL\Api\Data\CompanyInterface as ApiCompany;
use Hokodo\BNPL\Api\Data\Gateway\CompanySearchRequestInterface;
use Hokodo\BNPL\Api\Data\Gateway\CompanySearchRequestInterfaceFactory;
use Hokodo\BNPL\Gateway\Service\CompanySearch as Gateway;
use Hokodo\BNPL\Gateway\Service\Organisation;
use Hokodo\BNPL\Model\HokodoCompanyProvider;
use Hokodo\BNPL\Model\RequestBuilder\OrganisationBuilder;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Api\SessionCleanerInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\NotFoundException;
use Magento\Framework\Notification\NotifierInterface;
use Magento\Payment\Gateway\Command\CommandException;
use Psr\Log\LoggerInterface;

class CompanyImport
{
    public const TOPIC_NAME = 'hokodo.company.import';

    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * @var CustomerRepositoryInterface
     */
    private CustomerRepositoryInterface $customerRepository;

    /**
     * @var HokodoCompanyProvider
     */
    private HokodoCompanyProvider $hokodoCompanyProvider;

    /**
     * @var CompanySearchRequestInterfaceFactory
     */
    private CompanySearchRequestInterfaceFactory $companySearchRequestFactory;

    /**
     * @var Gateway
     */
    private Gateway $gateway;

    /**
     * @var SessionCleanerInterface
     */
    private SessionCleanerInterface $sessionCleanerInterface;

    /**
     * @var NotifierInterface
     */
    private NotifierInterface $notifierPool;

    /**
     * @var array
     */
    private array $companyImportData = [];

    /**
     * @var Organisation
     */
    private Organisation $organisationService;

    /**
     * @var OrganisationBuilder
     */
    private OrganisationBuilder $organisationBuilder;

    /**
     * @var CompanyCreditServiceInterface
     */
    private CompanyCreditServiceInterface $companyCreditService;

    /**
     * @param CustomerRepositoryInterface          $customerRepository
     * @param HokodoCompanyProvider                $hokodoCompanyProvider
     * @param CompanySearchRequestInterfaceFactory $companySearchRequestFactory
     * @param Gateway                              $gateway
     * @param Organisation                         $organisationService
     * @param OrganisationBuilder                  $organisationBuilder
     * @param CompanyCreditServiceInterface        $companyCreditService
     * @param SessionCleanerInterface              $sessionCleanerInterface
     * @param NotifierInterface                    $notifierPool
     * @param LoggerInterface                      $logger
     */
    public function __construct(
        CustomerRepositoryInterface $customerRepository,
        HokodoCompanyProvider $hokodoCompanyProvider,
        CompanySearchRequestInterfaceFactory $companySearchRequestFactory,
        Gateway $gateway,
        Organisation $organisationService,
        OrganisationBuilder $organisationBuilder,
        CompanyCreditServiceInterface $companyCreditService,
        SessionCleanerInterface $sessionCleanerInterface,
        NotifierInterface $notifierPool,
        LoggerInterface $logger
    ) {
        $this->customerRepository = $customerRepository;
        $this->hokodoCompanyProvider = $hokodoCompanyProvider;
        $this->companySearchRequestFactory = $companySearchRequestFactory;
        $this->gateway = $gateway;
        $this->organisationService = $organisationService;
        $this->organisationBuilder = $organisationBuilder;
        $this->companyCreditService = $companyCreditService;
        $this->sessionCleanerInterface = $sessionCleanerInterface;
        $this->notifierPool = $notifierPool;
        $this->logger = $logger;
    }

    /**
     * Execute queue message handler.
     *
     * @param CompanyImportInterface $companyImport
     *
     * @return void
     */
    public function execute(CompanyImportInterface $companyImport): void
    {
        try {
            $this->initCompanyImportData($companyImport);
            $this->logger->info(__METHOD__, $this->companyImportData);

            $customer = $this->customerRepository->get($companyImport->getEmail(), $companyImport->getWebsiteId());

            $hokodoCompanyId = $this->getHokodoCompanyId($companyImport);

            $hokodoEntity = $this->hokodoCompanyProvider
                ->getEntityRepository()->getByCustomerId((int) $customer->getId());

            if ($hokodoEntity->getCompanyId() != $hokodoCompanyId) {
                $organisationId = $this->getOrganisationId($hokodoCompanyId, $companyImport);
                $creditLimit = $this->companyCreditService->getCreditLimit($hokodoCompanyId);

                $hokodoEntity->setCompanyId($hokodoCompanyId);
                $hokodoEntity->setOrganisationId($organisationId);
                $hokodoEntity->setCreditLimit($creditLimit);
                $this->updateHokodoEntity($hokodoEntity, (int) $customer->getId());
            }
        } catch (NotFoundException|CommandException $e) {
            $this->companyImportData['message'] = 'Can not find company.';
            $this->companyImportData['error'] = $e->getMessage();
            $this->processError($this->companyImportData, __METHOD__);
        } catch (NoSuchEntityException|LocalizedException $e) {
            $this->companyImportData['message'] = sprintf(
                'Can not update customer %s. Customer not found.',
                $companyImport->getEmail()
            );
            $this->companyImportData['error'] = $e->getMessage();
            $this->processError($this->companyImportData, __METHOD__);
        } catch (\Exception $e) {
            $this->companyImportData['message'] = sprintf(
                'Hokodo_BNPL: Error processing customer with email %s.',
                $companyImport->getEmail()
            );
            $this->companyImportData['error'] = $e->getMessage();
            $this->processError($this->companyImportData, __METHOD__);
        }
    }

    /**
     * Get Hokodo Company Id.
     *
     * @param CompanyImportInterface $companyImport
     *
     * @return string|null
     *
     * @throws CommandException
     * @throws NotFoundException
     */
    private function getHokodoCompanyId(CompanyImportInterface $companyImport): ?string
    {
        $companyId = $companyImport->getCompanyId();
        if (!$companyId) {
            $hokodoCompany = $this->getCompanyFromHokodo(
                $companyImport->getRegNumber(),
                $companyImport->getCountryCode()
            );
            $companyId = $hokodoCompany->getId();
        }
        return $companyId;
    }

    /**
     * Get Organisation.
     *
     * @param string                 $companyId
     * @param CompanyImportInterface $companyImport
     *
     * @return string|null
     *
     * @throws CommandException
     * @throws NoSuchEntityException
     * @throws NotFoundException
     */
    private function getOrganisationId(string $companyId, CompanyImportInterface $companyImport): ?string
    {
        $organisationId = $companyImport->getOrganisationId();
        if (!$organisationId) {
            $organisation = $this->organisationService->createOrganisation(
                $this->organisationBuilder->build(
                    $companyId,
                    $companyImport->getEmail()
                )
            )->getDataModel();
            $organisationId = $organisation->getId();
        }
        return $organisationId;
    }

    /**
     * Init Data.
     *
     * @param CompanyImportInterface $companyImport
     *
     * @return void
     */
    public function initCompanyImportData(CompanyImportInterface $companyImport): void
    {
        $this->companyImportData = [
            CompanyImportInterface::EMAIL => $companyImport->getEmail(),
            CompanyImportInterface::REG_NUMBER => $companyImport->getRegNumber(),
            CompanyImportInterface::COUNTRY_CODE => $companyImport->getCountryCode(),
            CompanyImportInterface::WEBSITE_ID => $companyImport->getWebsiteId(),
            CompanyImportInterface::COMPANY_ID => $companyImport->getCompanyId(),
            CompanyImportInterface::ORGANISATION_ID => $companyImport->getOrganisationId(),
        ];
    }

    /**
     * Get Company from Hokodo.
     *
     * @param string $regNumber
     * @param string $countryCode
     *
     * @return ApiCompany|null
     *
     * @throws NotFoundException|CommandException
     */
    private function getCompanyFromHokodo(string $regNumber, string $countryCode): ?ApiCompany
    {
        $hokodoCompany = null;
        /** @var CompanySearchRequestInterface $searchRequest */
        $searchRequest = $this->companySearchRequestFactory->create();
        $searchRequest
            ->setCountry($countryCode)
            ->setRegNumber($regNumber);
        if ($list = $this->gateway->search($searchRequest)->getList()) {
            $hokodoCompany = reset($list);
        }
        if (!$hokodoCompany || !$hokodoCompany->getId()) {
            throw new NotFoundException(__('Hokodo Company not found'));
        }
        return $hokodoCompany;
    }

    /**
     * Update Hokodo Entity.
     *
     * @param mixed $hokodoEntity
     * @param int   $customerId
     *
     * @return void
     */
    public function updateHokodoEntity($hokodoEntity, int $customerId): void
    {
        $hokodoEntity->setCustomerId($customerId);
        $this->hokodoCompanyProvider->getEntityRepository()->save($hokodoEntity);
        $this->sessionCleanerInterface->clearFor($customerId);
        $this->companyImportData['message'] = sprintf('Company was updated for customer %s.', $customerId);
        $this->companyImportData['hokodo_company_id'] = $hokodoEntity->getCompanyId();
        $this->logger->info(__METHOD__, $this->companyImportData);
    }

    /**
     * Log Error and Push System Message.
     *
     * @param array  $data
     * @param string $method
     *
     * @return void
     */
    public function processError(array $data, string $method): void
    {
        $this->logger->error($method, $data);
        $this->notifierPool->addMajor(
            __('Some errors occurred during Hokodo Company Import.'),
            __('Please check hokodo_import_error.log'),
            ''
        );
    }
}
