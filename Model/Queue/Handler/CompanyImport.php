<?php
/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Hokodo\BNPL\Model\Queue\Handler;

use Hokodo\BNPL\Api\Data\CompanyImportInterface;
use Hokodo\BNPL\Api\Data\CompanyInterface as ApiCompany;
use Hokodo\BNPL\Api\Data\Gateway\CompanySearchRequestInterface;
use Hokodo\BNPL\Api\Data\Gateway\CompanySearchRequestInterfaceFactory;
use Hokodo\BNPL\Gateway\Service\CompanySearch as Gateway;
use Hokodo\BNPL\Model\HokodoCompanyProvider;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Api\Data\CustomerInterface;
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
    private array $data = [];

    /**
     * @param CustomerRepositoryInterface          $customerRepository
     * @param HokodoCompanyProvider                $hokodoCompanyProvider
     * @param CompanySearchRequestInterfaceFactory $companySearchRequestFactory
     * @param Gateway                              $gateway
     * @param SessionCleanerInterface              $sessionCleanerInterface
     * @param NotifierInterface                    $notifierPool
     * @param LoggerInterface                      $logger
     */
    public function __construct(
        CustomerRepositoryInterface $customerRepository,
        HokodoCompanyProvider $hokodoCompanyProvider,
        CompanySearchRequestInterfaceFactory $companySearchRequestFactory,
        Gateway $gateway,
        SessionCleanerInterface $sessionCleanerInterface,
        NotifierInterface $notifierPool,
        LoggerInterface $logger
    ) {
        $this->customerRepository = $customerRepository;
        $this->hokodoCompanyProvider = $hokodoCompanyProvider;
        $this->companySearchRequestFactory = $companySearchRequestFactory;
        $this->gateway = $gateway;
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
            $this->data = [
                CompanyImportInterface::EMAIL => $companyImport->getEmail(),
                CompanyImportInterface::REG_NUMBER => $companyImport->getRegNumber(),
                CompanyImportInterface::COUNTRY_CODE => $companyImport->getCountryCode(),
            ];
            $this->logger->info(__METHOD__, $this->data);

            $customer = $this->getCustomer($companyImport->getEmail());
            if (!$customer) {
                return;
            }
            /* @todo refactor getByCustomerId() repository must throw exception */
            $hokodoEntity = $this->hokodoCompanyProvider
                ->getEntityRepository()->getByCustomerId((int) $customer->getId());

            $hokodoCompany = $this->getCompanyFromHokodo(
                $companyImport->getRegNumber(),
                $companyImport->getCountryCode()
            );

            if (!$hokodoCompany || !$hokodoCompany->getId()) {
                $this->data['message'] = "Company Id not found, regnumber: {$companyImport->getRegNumber()}";
                $this->processError($this->data, __METHOD__);
                return;
            }

            if ($hokodoEntity->getCompanyId() != $hokodoCompany->getId()) {
                $hokodoEntity
                    ->setCustomerId((int) $customer->getId())
                    ->setCompanyId($hokodoCompany->getId());
                $this->hokodoCompanyProvider->getEntityRepository()->save($hokodoEntity);
                $this->sessionCleanerInterface->clearFor((int) $customer->getId());
                $this->data['message'] = "Company was updated for customer {$customer->getId()}.";
                $this->data['hokodo_company_id'] = $hokodoCompany->getId();
                $this->logger->info(__METHOD__, $this->data);
            }
        } catch (\Exception $e) {
            $this->data['message'] = "Hokodo_BNPL: Error processing customer with email {$companyImport->getEmail()}.";
            $this->data['error'] = $e->getMessage();
            $this->processError($this->data, __METHOD__);
        }
    }

    /**
     * Get Customer.
     *
     * @param string $email
     *
     * @return CustomerInterface|null
     */
    public function getCustomer(string $email): ?CustomerInterface
    {
        $customer = null;
        try {
            $customer = $this->customerRepository->get($email);
        } catch (NoSuchEntityException|LocalizedException $e) {
            $this->data['error'] = $e->getMessage();
            $this->data['message'] = "Can not update customer {$email}. Customer not found.";
            $this->processError($this->data, __METHOD__);
        }
        return $customer;
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

    /**
     * Get Company Id from Hokodo.
     *
     * @param string $regNumber
     * @param string $countryCode
     *
     * @return ApiCompany|null
     */
    private function getCompanyFromHokodo(string $regNumber, string $countryCode): ?ApiCompany
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
                'message' => 'Can not find company. ' . $e->getMessage(),
                'regnumber' => $regNumber,
                'countrycode' => $countryCode,
            ];
            $this->logger->error(__METHOD__, $data);
        }
        return null;
    }
}
