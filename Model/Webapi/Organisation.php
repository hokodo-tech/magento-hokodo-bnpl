<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Hokodo\BNPL\Model\Webapi;

use Hokodo\BNPL\Api\Data\Gateway\CreateOrganisationRequestInterfaceFactory;
use Hokodo\BNPL\Api\Data\Webapi\CreateOrganisationRequestInterface;
use Hokodo\BNPL\Api\Data\Webapi\CreateOrganisationResponseInterface;
use Hokodo\BNPL\Api\Data\Webapi\CreateOrganisationResponseInterfaceFactory;
use Hokodo\BNPL\Api\Webapi\OrganisationInterface;
use Hokodo\BNPL\Gateway\Service\Organisation as OrganisationService;
use Magento\Store\Model\StoreManagerInterface;

class Organisation implements OrganisationInterface
{
    private OrganisationService $organisationService;

    private CreateOrganisationRequestInterfaceFactory $createOrganisationGatewayRequestFactory;

    private StoreManagerInterface $storeManager;

    private CreateOrganisationResponseInterfaceFactory $createOrganisationResponseFactory;

    public function __construct(
        OrganisationService $organisationService,
        CreateOrganisationRequestInterfaceFactory $createOrganisationGatewayRequestFactory,
        StoreManagerInterface $storeManager,
        CreateOrganisationResponseInterfaceFactory $createOrganisationResponseFactory
    ) {
        $this->organisationService = $organisationService;
        $this->createOrganisationGatewayRequestFactory = $createOrganisationGatewayRequestFactory;
        $this->storeManager = $storeManager;
        $this->createOrganisationResponseFactory = $createOrganisationResponseFactory;
    }

    /**
     * @param CreateOrganisationRequestInterface $payload
     *
     * @return CreateOrganisationResponseInterface
     */
    public function create(CreateOrganisationRequestInterface $payload): CreateOrganisationResponseInterface
    {
        //TODO create Organisation Logic
        $result = $this->createOrganisationResponseFactory->create();
        try {
            $gatewayRequest = $this->createOrganisationGatewayRequestFactory->create();
            $gatewayRequest
                ->setCompanyId($payload->getCompanyId())
                ->setUniqueId('mage-org-' . hash('md5', $this->storeManager->getStore()->getCode() . $this->storeManager->getStore()->getName() . $payload->getCompanyId()))
                ->setRegistered(date('Y-m-d\TH:i:s\Z'));
            $organisation = $this->organisationService->createOrganisation($gatewayRequest);
            if ($dataModel = $organisation->getDataModel()) {
                $result->setId($dataModel->getId());
            }
        } catch (\Exception $e) {
            //TODO add error resolving
        }
        return $result;
    }
}
