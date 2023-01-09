<?php
/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Hokodo\BNPL\Model;

use Exception;
use Hokodo\BNPL\Api\Data\CompanyInterface;
use Hokodo\BNPL\Api\Data\CompanyInterfaceFactory;
use Hokodo\BNPL\Api\Data\HokodoOrganisationInterface;
use Hokodo\BNPL\Api\Data\HokodoOrganisationInterfaceFactory;
use Hokodo\BNPL\Api\Data\OrganisationInterface;
use Hokodo\BNPL\Api\Data\OrganisationInterfaceFactory;
use Hokodo\BNPL\Api\HokodoOrganisationCheckingInterface;
use Hokodo\BNPL\Api\HokodoOrganisationManagementInterface;
use Hokodo\BNPL\Api\HokodoOrganisationRepositoryInterface;
use Hokodo\BNPL\Service\CompanyServiceFactory;
use Hokodo\BNPL\Service\OrganisationService;
use Hokodo\BNPL\Service\OrganisationServiceFactory;
use Magento\Framework\DataObject\Mapper;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Psr\Log\LoggerInterface as Logger;

class HokodoOrganisationManagement implements HokodoOrganisationManagementInterface
{
    /**
     * @var array
     */
    private array $companyMap = [
        CompanyInterface::COUNTRY => HokodoOrganisationInterface::COUNTRY,
        CompanyInterface::ADDRESS => HokodoOrganisationInterface::ADDRESS,
        CompanyInterface::CITY => HokodoOrganisationInterface::CITY,
        CompanyInterface::POSTCODE => HokodoOrganisationInterface::POSTCODE,
        CompanyInterface::EMAIL => HokodoOrganisationInterface::EMAIL,
        CompanyInterface::PHONE => HokodoOrganisationInterface::PHONE,
        CompanyInterface::ID => HokodoOrganisationInterface::COMPANY_API_ID,
    ];

    /**
     * @var HokodoOrganisationRepositoryInterface
     */
    private HokodoOrganisationRepositoryInterface $organisationRepository;

    /**
     * @var HokodoOrganisationInterfaceFactory
     */
    private HokodoOrganisationInterfaceFactory $hokodoOrganisationFactory;

    /**
     * @var OrganizationService
     */
    private OrganizationService $organisationService;

    /**
     * @var OrganisationServiceFactory
     */
    private OrganisationServiceFactory $organisationServiceFactory;

    /**
     * @var OrganisationInterfaceFactory
     */
    private OrganisationInterfaceFactory $organisationFactory;

    /**
     * @var CompanyServiceFactory
     */
    private CompanyServiceFactory $companyServiceFactory;

    /**
     * @var CompanyInterfaceFactory
     */
    private CompanyInterfaceFactory $companyFactory;

    /**
     * @var HokodoOrganisationCheckingInterface
     */
    private HokodoOrganisationCheckingInterface $orgChecking;

    /**
     * @var Logger
     */
    private Logger $logger;

    /**
     * A constructor.
     *
     * @param HokodoOrganisationRepositoryInterface $organisationRepository
     * @param HokodoOrganisationInterfaceFactory    $hokodoOrganisationFactory
     * @param OrganisationServiceFactory            $organisationServiceFactory
     * @param OrganisationInterfaceFactory          $organisationFactory
     * @param CompanyServiceFactory                 $companyServiceFactory
     * @param CompanyInterfaceFactory               $companyFactory
     * @param HokodoOrganisationCheckingInterface   $orgChecking
     * @param Logger                                $logger
     */
    public function __construct(
        HokodoOrganisationRepositoryInterface $organisationRepository,
        HokodoOrganisationInterfaceFactory $hokodoOrganisationFactory,
        OrganisationServiceFactory $organisationServiceFactory,
        OrganisationInterfaceFactory $organisationFactory,
        CompanyServiceFactory $companyServiceFactory,
        CompanyInterfaceFactory $companyFactory,
        HokodoOrganisationCheckingInterface $orgChecking,
        Logger $logger
    ) {
        $this->organisationRepository = $organisationRepository;
        $this->hokodoOrganisationFactory = $hokodoOrganisationFactory;
        $this->organisationServiceFactory = $organisationServiceFactory;
        $this->organisationFactory = $organisationFactory;
        $this->companyServiceFactory = $companyServiceFactory;
        $this->companyFactory = $companyFactory;
        $this->orgChecking = $orgChecking;
        $this->logger = $logger;
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\HokodoOrganisationManagementInterface::getUserOrganisation()
     */
    public function getUserOrganisation($organisationApiId)
    {
        $isGuest = $this->orgChecking->isGuest();
        try {
            if (!$isGuest) {
                $organisation = $this->organisationRepository->getByApiId($organisationApiId);
            } else {
                $organisation = $this->createOrganisationFromApi($organisationApiId);
            }
        } catch (NoSuchEntityException $e) {
            $organisation = $this->createOrganisationFromApi($organisationApiId);
        } catch (LocalizedException $e) {
            $data = [
                'payment_log_content' => $e->getMessage(),
                'action_title' => 'HokodoOrganisationManagement::getUserOrganisation LocalizedException',
                'status' => 0,
            ];
            $this->logger->error(__METHOD__, $data);
            return null;
        } catch (Exception $e) {
            $data = [
                'payment_log_content' => $e->getMessage(),
                'action_title' => 'HokodoOrganisationManagement::getUserOrganisation Exception',
                'status' => 0,
            ];
            $this->logger->error(__METHOD__, $data);
            return null;
        }
        return $organisation;
    }

    /**
     * A function gets created organisation.
     *
     * @param HokodoOrganisationInterface $organisation
     *
     * @return HokodoOrganisationInterface
     *
     * @throws LocalizedException
     */
    public function getCreatedOrganisation(HokodoOrganisationInterface $organisation)
    {
        try {
            $createdOrganisation = $this->organisationRepository->getByApiId($organisation->getApiId());
        } catch (NoSuchEntityException $e) {
            $createdOrganisation = $this->createOrganization($organisation);
        } catch (LocalizedException $e) {
            $data = [
                'payment_log_content' => $e->getMessage(),
                'action_title' => 'HokodoOrganisationManagement::getCreatedOrganisation LocalizedException',
                'status' => 0,
            ];
            $this->logger->error(__METHOD__, $data);
            return null;
        } catch (Exception $e) {
            $data = [
                'payment_log_content' => $e->getMessage(),
                'action_title' => 'HokodoOrganisationManagement::getCreatedOrganisation Exception',
                'status' => 0,
            ];
            $this->logger->error(__METHOD__, $data);
            return null;
        }
        return $createdOrganisation;
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\HokodoOrganisationManagementInterface::createOrganization()
     */
    private function createOrganization(HokodoOrganisationInterface $organisation)
    {
        try {
            $this->organisationRepository->save($organisation);
            $this->createApiOrganisation($organisation);
            $this->organisationRepository->save($organisation);
        } catch (CouldNotSaveException $e) {
            $data = [
                'payment_log_content' => $e->getMessage(),
                'action_title' => 'HokodoOrganisationManagement::createOrganization CouldNotSaveException',
                'status' => 0,
            ];
            $this->logger->error(__METHOD__, $data);
            return null;
        } catch (LocalizedException $e) {
            $data = [
                'payment_log_content' => $e->getMessage(),
                'action_title' => 'HokodoOrganisationManagement::createOrganization LocalizedException',
                'status' => 0,
            ];
            $this->logger->error(__METHOD__, $data);
            return null;
        } catch (Exception $e) {
            $data = [
                'payment_log_content' => $e->getMessage(),
                'action_title' => 'HokodoOrganisationManagement::createOrganization Exception',
                'status' => 0,
            ];
            $this->logger->error(__METHOD__, $data);
            return null;
        }
        return $organisation;
    }

    /**
     * A function that creates organisations form.
     *
     * @param string $organisationApiId
     *
     * @return HokodoOrganisationInterface|null
     *
     * @throws CouldNotSaveException
     * @throws NoSuchEntityException
     */
    private function createOrganisationFromApi($organisationApiId)
    {
        /** @var OrganisationInterface $organisation */
        $organisation = $this->organisationFactory->create();
        $organisation->setId($organisationApiId);

        /** @var OrganisationInterface $organisationResult */
        $organisationResult = $this->getOrganisationService()->get($organisation);

        if ($organisationResult->getCompany()) {
            /** @var CompanyInterface $company */
            $company = $this->companyFactory->create();
            $company->setId($organisationResult->getCompany());
            /** @var CompanyInterface $companyResult */
            $companyResult = $this->companyServiceFactory->create()->get($company);
            if ($companyResult->getId()) {
                //Create new Hokodo Organisation
                $isGuest = $this->orgChecking->isGuest();
                $orgExist = $this->organisationRepository->getExistingApiId($organisationApiId);
                if ($isGuest || !$orgExist) {
                    /** @var OrganisationInterface $newOrganisation */
                    $newOrganisation = $this->organisationFactory->create();
                    $newOrganisation->setUniqueId('ord-unid-' . time());
                    $newOrganisation->setRegistered(date('Y-m-d\TH:i:s\Z'));
                    $newOrganisation->setCompany($companyResult->getId());
                    /** @var OrganisationInterface $newOrganisationResult */
                    $newOrganisationResult = $this->getOrganisationService()->create($newOrganisation);
                    if ($newOrganisationResult->getId()) {
                        $organisationApiId = $newOrganisationResult->getId();
                    }
                }
                /** @var HokodoOrganisationInterface $hokodoOrganisation */
                $hokodoOrganisation = $this->hokodoOrganisationFactory->create();
                Mapper::accumulateByMap(
                    $companyResult->__toArray(),
                    [$hokodoOrganisation, 'setDataUsingMethod'],
                    $this->companyMap
                );
                $hokodoOrganisation->setName($companyResult->getName());
                $hokodoOrganisation->setApiId($organisationApiId);
                $this->organisationRepository->save($hokodoOrganisation);
                return $hokodoOrganisation;
            }
        }
        return null;
    }

    /**
     * A function that creates api organisation.
     *
     * @param HokodoOrganisationInterface $organization
     *
     * @return void
     *
     * @throws LocalizedException
     */
    private function createApiOrganisation(HokodoOrganisationInterface $organization)
    {
        /** @var OrganisationInterface $result */
        $result = $this->getOrganisationService()->create($organization->getDataModel());
        try {
            $organization->setApiId($result->getId());
            $this->organisationRepository->save($organization);
        } catch (Exception $e) {
            $data = [
                'payment_log_content' => $e->getMessage(),
                'action_title' => 'HokodoOrganisationManagement::createApiOrganisation Exception',
                'status' => 0,
            ];
            $this->logger->error(__METHOD__, $data);
        }
    }

    /**
     * A function that gets organisation service.
     *
     * @return OrganisationService
     */
    private function getOrganisationService()
    {
        if (null == $this->organisationService) {
            $this->organisationService = $this->organisationServiceFactory->create();
        }

        return $this->organisationService;
    }
}
