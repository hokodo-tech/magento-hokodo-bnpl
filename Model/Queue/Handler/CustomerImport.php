<?php
/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Hokodo\BNPL\Model\Queue\Handler;

use Hokodo\BNPL\Api\Data\CompanyInterface as ApiCompany;
use Hokodo\BNPL\Api\Data\CustomerImportInterface;
use Hokodo\BNPL\Api\Data\Gateway\CompanySearchRequestInterface;
use Hokodo\BNPL\Api\Data\Gateway\CompanySearchRequestInterfaceFactory;
use Hokodo\BNPL\Gateway\Service\CompanySearch as Gateway;
use Hokodo\BNPL\Model\HokodoCompanyProvider;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Api\SessionCleanerInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\NotFoundException;
use Magento\Framework\Message\ManagerInterface as MessageManagerInterface;
use Magento\Payment\Gateway\Command\CommandException;
use Psr\Log\LoggerInterface;

class CustomerImport
{
    public const TOPIC_NAME = 'hokodo.customer.import';

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
     * @var MessageManagerInterface
     */
    private $messageManager;

    /**
     * @param CustomerRepositoryInterface $customerRepository
     * @param HokodoCompanyProvider $hokodoCompanyProvider
     * @param CompanySearchRequestInterfaceFactory $companySearchRequestFactory
     * @param Gateway $gateway
     * @param SessionCleanerInterface $sessionCleanerInterface
     * @param LoggerInterface $logger
     */
    public function __construct(
        CustomerRepositoryInterface $customerRepository,
        HokodoCompanyProvider $hokodoCompanyProvider,
        CompanySearchRequestInterfaceFactory $companySearchRequestFactory,
        Gateway $gateway,
        SessionCleanerInterface $sessionCleanerInterface,
        MessageManagerInterface $messageManager,
        LoggerInterface $logger
    ) {
        $this->customerRepository = $customerRepository;
        $this->hokodoCompanyProvider = $hokodoCompanyProvider;
        $this->companySearchRequestFactory = $companySearchRequestFactory;
        $this->gateway = $gateway;
        $this->sessionCleanerInterface = $sessionCleanerInterface;
        $this->logger = $logger;
        $this->messageManager = $messageManager;
    }

    /**
     * Execute queue message handler.
     *
     * @param CustomerImportInterface $customerImport
     *
     * @return void
     */
    public function execute(CustomerImportInterface $customerImport): void
    {
        $hasError = false;
        try {
            $data = [
                CustomerImportInterface::EMAIL => $customerImport->getEmail(),
                CustomerImportInterface::REG_NUMBER => $customerImport->getRegNumber(),
                CustomerImportInterface::COUNTRY_CODE => $customerImport->getCountryCode(),
            ];
            $this->logger->info(__METHOD__, $data);

            try {
                $customer = $this->customerRepository->get($customerImport->getEmail());
            } catch (NoSuchEntityException|LocalizedException $e) {
                $data['error'] = $e->getMessage();
                throw new \Exception(
                    "Hokodo_BNPL: Can not update customer {$customerImport->getEmail()}. Customer not found."
                );
            }

            $hokodoEntity = $this->hokodoCompanyProvider
                ->getEntityRepository()->getByCustomerId((int) $customer->getId());

            $hokodoCompany = $this->getCompanyFromHokodo($customerImport->getRegNumber(), $customerImport->getCountryCode());

            if (!$hokodoCompany || !$hokodoCompany->getId()) {
                throw new \Exception(
                    "Hokodo_BNPL: Company Id not found regnumber: {$customerImport->getRegNumber()}."
                );
            }

            if ($hokodoEntity->getCompanyId() != $hokodoCompany->getId()) {
                $hokodoEntity
                    ->setCustomerId((int) $customer->getId())
                    ->setCompanyId($hokodoCompany->getId());
                $this->hokodoCompanyProvider->getEntityRepository()->save($hokodoEntity);
                $this->sessionCleanerInterface->clearFor((int) $customer->getId());
                $data['message'] = __("Hokodo_BNPL: Company was updated for customer %1.", $customer->getId());
                $data['hokodo_company_id'] = $hokodoCompany->getId();
                $this->logger->info(__METHOD__, $data);
            }
        } catch (\Exception $e) {
            $hasError = true;
            $data['message'] = __(
                "Hokodo_BNPL: Error processing customer with email %1.",
                $customerImport->getEmail()
            );
            $data['error'] = $e->getMessage();
            $this->logger->error(__METHOD__, $data);
        }
        if ($hasError) {
            var_dump($hasError);
            $this->messageManager
                ->addErrorMessage('Some errors occurred. Please check hokodo_import_error.log');
        }
    }

    /**
     * Get Company Id from Hokodo
     *
     * @param string $regNumber
     * @param string $countryCode
     * @return ApiCompany|null
     */
    private function getCompanyFromHokodo(string $regNumber, string $countryCode):?ApiCompany
    {
        /** @var CompanySearchRequestInterface $searchRequest */
        $searchRequest = $this->companySearchRequestFactory->create();
        $searchRequest
            ->setCountry($countryCode)
            ->setRegNumber($regNumber);
        try {
            if ($list = $this->gateway->search($searchRequest)->getList()) {
                return reset($list);
            }
        } catch (NotFoundException|CommandException $e) {
            $data = [
                'message' => __('Can not find company. %1', $e->getMessage()),
                'regnumber' => $regNumber,
                'countrycode' => $countryCode,
            ];
            $this->logger->error(__METHOD__, $data);
        }
        return null;
    }
}
