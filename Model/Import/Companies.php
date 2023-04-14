<?php
/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Hokodo\BNPL\Model\Import;

use Hokodo\BNPL\Api\Data\CompanyImportInterface;
use Hokodo\BNPL\Api\Data\CompanyImportInterfaceFactory;
use Hokodo\BNPL\Gateway\Config\Config;
use Hokodo\BNPL\Model\Config\Source\SdkCountries;
use Hokodo\BNPL\Model\HokodoCompanyProvider;
use Hokodo\BNPL\Model\Queue\Handler\CompanyImport as CompanyImportHandler;
use Hokodo\BNPL\Service\Website;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Directory\Api\CountryInformationAcquirerInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Json\Helper\Data as JsonHelper;
use Magento\Framework\MessageQueue\PublisherInterface;
use Magento\ImportExport\Helper\Data as ImportHelper;
use Magento\ImportExport\Model\Import;
use Magento\ImportExport\Model\Import\Entity\AbstractEntity;
use Magento\ImportExport\Model\Import\ErrorProcessing\ProcessingErrorAggregatorInterface;
use Magento\ImportExport\Model\ResourceModel\Helper;
use Magento\ImportExport\Model\ResourceModel\Import\Data;

class Companies extends AbstractEntity
{
    public const ENTITY_CODE = 'hokodo_companies';
    public const EMAIL_COLUMN = 'email';
    public const REG_NUMBER_COLUMN = 'regnumber';
    public const COUNTRY_CODE_COLUMN = 'countrycode';
    public const WEBSITE_CODE_COLUMN = 'website';

    /**
     * @var bool
     */
    protected $needColumnCheck = true;

    /**
     * @var bool
     */
    protected $logInHistory = true;

    /**
     * @var string[]
     */
    protected $_permanentAttributes = [
        self::EMAIL_COLUMN,
        self::REG_NUMBER_COLUMN,
        self::COUNTRY_CODE_COLUMN,
        self::WEBSITE_CODE_COLUMN,
    ];

    /**
     * @var string[]
     */
    protected $validColumnNames = [
        self::EMAIL_COLUMN,
        self::REG_NUMBER_COLUMN,
        self::COUNTRY_CODE_COLUMN,
        self::WEBSITE_CODE_COLUMN,
    ];

    /**
     * @var CountryInformationAcquirerInterface
     */
    private CountryInformationAcquirerInterface $countryInformationAcquirer;

    /**
     * @var array
     */
    private array $countryCodes = [];

    /**
     * @var CustomerRepositoryInterface
     */
    private CustomerRepositoryInterface $customerRepository;

    /**
     * @var HokodoCompanyProvider
     */
    private HokodoCompanyProvider $hokodoCompanyProvider;

    /**
     * @var PublisherInterface
     */
    private PublisherInterface $publisher;

    /**
     * @var CompanyImportInterfaceFactory
     */
    private CompanyImportInterfaceFactory $companyImportInterfaceFactory;

    /**
     * @var Website
     */
    private Website $websiteService;

    /**
     * @var Config
     */
    private Config $config;

    /**
     * @var SdkCountries
     */
    private SdkCountries $sdkCountries;

    /**
     * @var CompanyImportHandler
     */
    private CompanyImportHandler $companyImportHandler;

    /**
     * @var array
     */
    private array $websites = [];

    /**
     * @param JsonHelper                          $jsonHelper
     * @param ImportHelper                        $importExportData
     * @param Data                                $importData
     * @param Helper                              $resourceHelper
     * @param ProcessingErrorAggregatorInterface  $errorAggregator
     * @param CountryInformationAcquirerInterface $countryInformationAcquirer
     * @param CustomerRepositoryInterface         $customerRepository
     * @param HokodoCompanyProvider               $hokodoCompanyProvider
     * @param Config                              $config
     * @param PublisherInterface                  $publisher
     * @param CompanyImportInterfaceFactory       $companyImportInterfaceFactory
     * @param Website                             $websiteService
     * @param SdkCountries                        $sdkCountries
     * @param CompanyImportHandler                $companyImportHandler
     */
    public function __construct(
        JsonHelper $jsonHelper,
        ImportHelper $importExportData,
        Data $importData,
        Helper $resourceHelper,
        ProcessingErrorAggregatorInterface $errorAggregator,
        CountryInformationAcquirerInterface $countryInformationAcquirer,
        CustomerRepositoryInterface $customerRepository,
        HokodoCompanyProvider $hokodoCompanyProvider,
        Config $config,
        PublisherInterface $publisher,
        CompanyImportInterfaceFactory $companyImportInterfaceFactory,
        Website $websiteService,
        SdkCountries $sdkCountries,
        CompanyImportHandler $companyImportHandler
    ) {
        $this->jsonHelper = $jsonHelper;
        $this->_importExportData = $importExportData;
        $this->_resourceHelper = $resourceHelper;
        $this->_dataSourceModel = $importData;
        $this->errorAggregator = $errorAggregator;
        $this->countryInformationAcquirer = $countryInformationAcquirer;
        $this->customerRepository = $customerRepository;
        $this->hokodoCompanyProvider = $hokodoCompanyProvider;
        $this->config = $config;
        $this->publisher = $publisher;
        $this->companyImportInterfaceFactory = $companyImportInterfaceFactory;
        $this->websiteService = $websiteService;
        $this->sdkCountries = $sdkCountries;
        $this->companyImportHandler = $companyImportHandler;
        $this->initMessageTemplates();
        $this->init();
    }

    /**
     * Make website column non required.
     *
     * @return void
     */
    private function init(): void
    {
        $this->websites = $this->websiteService->getWebsites();
        if (count($this->websites) == 1) {
            foreach ($this->_permanentAttributes as $key => $val) {
                if ($val == self::WEBSITE_CODE_COLUMN) {
                    unset($this->_permanentAttributes[$key]);
                    break;
                }
            }
        }
    }

    /**
     * Import data.
     *
     * @return bool
     *
     * @throws Exception
     */
    protected function _importData(): bool
    {
        $this->importCompanies();
        return true;
    }

    /**
     * Import Companies.
     *
     * @return void
     */
    private function importCompanies(): void
    {
        $behavior = $this->getBehavior();
        while ($bunch = $this->_dataSourceModel->getNextBunch()) {
            foreach ($bunch as $rowNum => $row) {
                if (!$this->validateRow($row, $rowNum)) {
                    continue;
                }

                if ($this->getErrorAggregator()->hasToBeTerminated()) {
                    $this->getErrorAggregator()->addRowToSkip($rowNum);
                    continue;
                }

                $email = $row[self::EMAIL_COLUMN];

                try {
                    $websiteId = $this->getWebsiteId($row);
                    $customer = $this->customerRepository->get($email, $websiteId);
                } catch (NoSuchEntityException|LocalizedException $e) {
                    $this->addRowError('CustomerNotFound', $rowNum);
                    $this->getErrorAggregator()->addRowToSkip($rowNum);
                    continue;
                }

                $hokodoEntity = $this->hokodoCompanyProvider
                    ->getEntityRepository()->getByCustomerId((int) $customer->getId());

                if ($hokodoEntity->getCompanyId() && $behavior != Import::BEHAVIOR_REPLACE) {
                    continue;
                }

                /** @var CompanyImportInterface $companyImport */
                $companyImport = $this->companyImportInterfaceFactory->create();
                $companyImport->setEmail($row[self::EMAIL_COLUMN])
                    ->setRegNumber($row[self::REG_NUMBER_COLUMN])
                    ->setCountryCode($row[self::COUNTRY_CODE_COLUMN])
                    ->setWebsiteId($websiteId);

                $this->publisher->publish(CompanyImportHandler::TOPIC_NAME, $companyImport);

                if ($hokodoEntity->getCompanyId()) {
                    $this->countItemsUpdated++;
                } else {
                    $this->countItemsCreated++;
                }
            }
        }
    }

    /**
     * Entity type code getter.
     *
     * @return string
     */
    public function getEntityTypeCode(): string
    {
        return self::ENTITY_CODE;
    }

    /**
     * Row validation.
     *
     * @param array $rowData
     * @param int   $rowNum
     *
     * @return bool
     */
    public function validateRow(array $rowData, $rowNum): bool
    {
        if (!$this->isValidEmail($rowData[self::EMAIL_COLUMN])) {
            $this->addRowError('EmailIsNotValid', $rowNum);
        }

        if (count($this->websites) > 1 && empty($rowData[self::WEBSITE_CODE_COLUMN])) {
            $this->addRowError('WebsiteIsEmpty', $rowNum);
        } elseif (count($this->websites) > 1 && !$this->isWebsiteCodeValid($rowData[self::WEBSITE_CODE_COLUMN])) {
            $this->addRowError('WebsiteIsNotValid', $rowNum);
        }

        if (!$this->isCustomerExists($rowData)) {
            $this->addRowError('CustomerNotFound', $rowNum);
        }
        if (!$this->isCountryCodeValid($rowData[self::COUNTRY_CODE_COLUMN])) {
            $this->addRowError('CountryCodeIsNotValid', $rowNum);
        }
        $regNumber = $rowData[self::COUNTRY_CODE_COLUMN] ?? null;
        if (!$regNumber) {
            $this->addRowError('RegNumberIsNotValid', $rowNum);
        }

        if (isset($this->_validatedRows[$rowNum])) {
            return !$this->getErrorAggregator()->isRowInvalid($rowNum);
        }

        $this->_validatedRows[$rowNum] = true;

        return !$this->getErrorAggregator()->isRowInvalid($rowNum);
    }

    /**
     * Check is provided string is email.
     *
     * @param string $email
     *
     * @return bool
     */
    public function isValidEmail(string $email): bool
    {
        $isValid = false;
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $isValid = true;
        }
        return $isValid;
    }

    /**
     * Check if customer exists.
     *
     * @param array $rowData
     *
     * @return bool
     */
    public function isCustomerExists(array $rowData): bool
    {
        $isExists = false;
        $email = $rowData[self::EMAIL_COLUMN];
        // @codingStandardsIgnoreStart
        try {
            $websiteId = $this->getWebsiteId($rowData);
            $customer = $this->customerRepository->get($email, $websiteId);
            if ($customer->getId()) {
                $isExists = true;
            }
        } catch (\Exception $e) {
        }
        // @codingStandardsIgnoreEnd
        return $isExists;
    }

    /**
     * Get Website Id.
     *
     * @param array $rowData
     *
     * @return int|null
     */
    private function getWebsiteId(array $rowData): ?int
    {
        $websiteId = null;
        /*
         * We need to set website id = 0 because we can't use null.
         * See get() method of Magento\Customer\Api\CustomerRepositoryInterface.
         */
        if (count($this->websites) > 1) {
            $websiteId = 0;
        }
        if (count($this->websites) > 1
            && isset($rowData[self::WEBSITE_CODE_COLUMN])
            && isset($this->websites[$rowData[self::WEBSITE_CODE_COLUMN]])
        ) {
            $websiteId = (int) $this->websites[$rowData[self::WEBSITE_CODE_COLUMN]];
        }
        return $websiteId;
    }

    /**
     * Check country code.
     *
     * @param string $countryCode
     *
     * @return bool
     */
    public function isCountryCodeValid(string $countryCode): bool
    {
        $isValid = false;
        $countryCodes = $this->config->getSdkCountries();
        if (in_array($countryCode, $countryCodes)) {
            $isValid = true;
        }
        if ((empty($countryCodes) || trim(array_shift($countryCodes)) == '')
            && in_array($countryCode, $this->sdkCountries->getCountries())
        ) {
            $isValid = true;
        }
        return $isValid;
    }

    /**
     * Check Website Code.
     *
     * @param string $websiteCode
     *
     * @return bool
     */
    public function isWebsiteCodeValid(string $websiteCode): bool
    {
        $isValid = false;
        if (!empty($this->websites[$websiteCode])) {
            $isValid = true;
        }
        return $isValid;
    }

    /**
     * Init Error Messages.
     */
    private function initMessageTemplates()
    {
        $this->addMessageTemplate(
            'EmailIsNotValid',
            __('The email is not valid.')
        );
        $this->addMessageTemplate(
            'CountryCodeIsNotValid',
            __('The countrycode is not valid.')
        );
        $this->addMessageTemplate(
            'RegNumberIsNotValid',
            __('The regnumber cannot be empty.')
        );
        $this->addMessageTemplate(
            'WebsiteIsEmpty',
            __('Absent or empty website column.')
        );
        $this->addMessageTemplate(
            'WebsiteIsNotValid',
            __('Incorrect or empty value in website column.')
        );
        $this->addMessageTemplate(
            'CustomerNotFound',
            __('The customer is not found.')
        );
        $this->addMessageTemplate(
            'columnNotFound',
            __('We can\'t find required columns: %s.')
        );
        $this->addMessageTemplate(
            'invalidAttributeName',
            __('Header contains invalid column(s): %s')
        );
        $this->addMessageTemplate(
            'columnEmptyHeader',
            __('Columns number: %s have empty headers')
        );
        $this->addMessageTemplate(
            'columnNameInvalid',
            __('Column names: "%s" are invalid')
        );
    }
}
