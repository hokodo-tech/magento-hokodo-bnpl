<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Model;

use Hokodo\BNPL\Api\CompanySearchServiceInterface;
use Hokodo\BNPL\Api\Data\CompanyInterface;
use Hokodo\BNPL\Api\Data\HokodoOrganisationInterface;
use Hokodo\BNPL\Api\Data\HokodoOrganisationInterfaceFactory;
use Hokodo\BNPL\Service\CompanyService;

/**
 * Class Hokodo\BNPL\Model\CompanySearchService.
 */
class CompanySearchService implements CompanySearchServiceInterface
{
    /**
     * @var array
     */
    private $companyMap = [
        CompanyInterface::COUNTRY => HokodoOrganisationInterface::COUNTRY,
        CompanyInterface::ADDRESS => HokodoOrganisationInterface::ADDRESS,
        CompanyInterface::CITY => HokodoOrganisationInterface::CITY,
        CompanyInterface::POSTCODE => HokodoOrganisationInterface::POSTCODE,
        CompanyInterface::EMAIL => HokodoOrganisationInterface::EMAIL,
        CompanyInterface::PHONE => HokodoOrganisationInterface::PHONE,
        CompanyInterface::ID => HokodoOrganisationInterface::COMPANY_API_ID,
    ];

    /**
     * @var CompanyService
     */
    private $companyService;

    /**
     * @var HokodoOrganisationInterfaceFactory
     */
    private $organisationFactory;

    /**
     * A constructor.
     *
     * @param CompanyService                     $companyService
     * @param HokodoOrganisationInterfaceFactory $organisationFactory
     */
    public function __construct(
        CompanyService $companyService,
        HokodoOrganisationInterfaceFactory $organisationFactory
    ) {
        $this->companyService = $companyService;
        $this->organisationFactory = $organisationFactory;
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\CompanySearchServiceInterface::search()
     */
    public function search($companyName, $countryName)
    {
        $companies = $this->companyService->search($companyName, $countryName);

        $result = [];
        foreach ($companies as $company) {
            /**
             * @var HokodoOrganisationInterface $organisation
             */
            $organisation = $this->organisationFactory->create();

            \Magento\Framework\DataObject\Mapper::accumulateByMap(
                $company->__toArray(),
                [$organisation, 'setDataUsingMethod'],
                $this->companyMap
            );

            $organisation->setName($company->getName());
            $result[] = $organisation;
        }

        return $result;
    }
}
