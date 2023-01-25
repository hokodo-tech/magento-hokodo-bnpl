<?php
/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Hokodo\BNPL\Model\Import;

use Hokodo\BNPL\Api\Data\CustomerImportInterface;
use Hokodo\BNPL\Api\Data\CustomerImportInterfaceFactory;
use Hokodo\BNPL\Model\HokodoCompanyProvider;
use Hokodo\BNPL\Model\Queue\Handler\CustomerImport as CustomerImportHandler;
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
    const ENTITY_CODE = 'hokodo_companies';
    const EMAIL_COLUMN = 'email';
    const REG_NUMBER_COLUMN = 'regnumber';
    const COUNTRY_CODE_COLUMN = 'countrycode';

    /**
     * Is need to check column names.
     */
    protected $needColumnCheck = true;

    /**
     * Is need to log in import history.
     */
    protected $logInHistory = true;

    /**
     * Permanent entity columns.
     */
    protected $_permanentAttributes = [
        self::EMAIL_COLUMN,
        self::REG_NUMBER_COLUMN,
        self::COUNTRY_CODE_COLUMN
    ];

    /**
     * Valid column names
     */
    protected $validColumnNames = [
        self::EMAIL_COLUMN,
        self::REG_NUMBER_COLUMN,
        self::COUNTRY_CODE_COLUMN
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
     * @var CustomerImportInterfaceFactory
     */
    private CustomerImportInterfaceFactory $customerImportInterfaceFactory;

    /**
     * @param JsonHelper $jsonHelper
     * @param ImportHelper $importExportData
     * @param Data $importData
     * @param Helper $resourceHelper
     * @param ProcessingErrorAggregatorInterface $errorAggregator
     * @param CountryInformationAcquirerInterface $countryInformationAcquirer
     * @param CustomerRepositoryInterface $customerRepository
     * @param HokodoCompanyProvider $hokodoCompanyProvider
     * @param PublisherInterface $publisher
     * @param CustomerImportInterfaceFactory $customerImportInterfaceFactory
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
        PublisherInterface $publisher,
        CustomerImportInterfaceFactory $customerImportInterfaceFactory
    ) {
        $this->jsonHelper = $jsonHelper;
        $this->_importExportData = $importExportData;
        $this->_resourceHelper = $resourceHelper;
        $this->_dataSourceModel = $importData;
        $this->errorAggregator = $errorAggregator;
        $this->countryInformationAcquirer = $countryInformationAcquirer;
        $this->customerRepository = $customerRepository;
        $this->hokodoCompanyProvider = $hokodoCompanyProvider;
        $this->publisher = $publisher;
        $this->customerImportInterfaceFactory = $customerImportInterfaceFactory;
        $this->initMessageTemplates();
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

                try {
                    $customer = $this->customerRepository->get($row[self::EMAIL_COLUMN]);
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

                /** @var CustomerImportInterface $customerImport */
                $customerImport = $this->customerImportInterfaceFactory->create();
                $customerImport->setEmail($row[self::EMAIL_COLUMN])
                    ->setRegNumber($row[self::REG_NUMBER_COLUMN])
                    ->setCountryCode($row[self::COUNTRY_CODE_COLUMN]);

                $this->publisher->publish(CustomerImportHandler::TOPIC_NAME, $customerImport);

                if ($hokodoEntity->getCompanyId()) {
                    $this->countItemsUpdated += (int) isset($row[self::EMAIL_COLUMN]);
                } else {
                    $this->countItemsCreated += (int) !isset($row[self::EMAIL_COLUMN]);
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
     * @param int $rowNum
     *
     * @return bool
     */
    public function validateRow(array $rowData, $rowNum): bool
    {
        if (!$this->isValidEmail($rowData[self::EMAIL_COLUMN])) {
            $this->addRowError('EmailIsNotValid', $rowNum);
        }
        if (!$this->isCustomerExists($rowData[self::EMAIL_COLUMN])) {
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
     * @param string $email
     *
     * @return bool
     */
    public function isCustomerExists(string $email): bool
    {
        $isExists = false;
        try {
            $customer = $this->customerRepository->get($email);
            if ($customer->getId()) {
                $isExists = true;
            }
        } catch (NoSuchEntityException|LocalizedException $e) {
        }
        return $isExists;
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
        if (in_array($countryCode, $this->getCountryCodes())) {
            $isValid = true;
        }
        return $isValid;
    }

    /**
     * Get countries codes.
     *
     * @return array
     */
    public function getCountryCodes(): array
    {
        if (!empty($this->countryCodes)) {
            return $this->countryCodes;
        }
        $countryCodes = [];

        $countries = $this->countryInformationAcquirer->getCountriesInfo();
        foreach ($countries as $country) {
            $countryCodes[] = $country->getTwoLetterAbbreviation();
        }
        return $this->countryCodes = $countryCodes;
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
            'CustomerNotFound',
            __('The customer not found.')
        );
    }
}
