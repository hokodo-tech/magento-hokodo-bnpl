<?php
/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Hokodo\BNPL\Model\RequestBuilder;

use Hokodo\BNPL\Api\Data\Gateway\CreateOrganisationRequestInterface;
use Hokodo\BNPL\Api\Data\Gateway\CreateOrganisationRequestInterfaceFactory;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Model\StoreManagerInterface;

class OrganisationBuilder
{
    /**
     * @var CreateOrganisationRequestInterfaceFactory
     */
    private CreateOrganisationRequestInterfaceFactory $createOrganisationGatewayRequestFactory;

    /**
     * @var StoreManagerInterface
     */
    private StoreManagerInterface $storeManager;

    /**
     * @param CreateOrganisationRequestInterfaceFactory $createOrganisationGatewayRequestFactory
     * @param StoreManagerInterface                     $storeManager
     */
    public function __construct(
        CreateOrganisationRequestInterfaceFactory $createOrganisationGatewayRequestFactory,
        StoreManagerInterface $storeManager
    ) {
        $this->createOrganisationGatewayRequestFactory = $createOrganisationGatewayRequestFactory;
        $this->storeManager = $storeManager;
    }

    /**
     * Organisation request object builder.
     *
     * @param string $companyId
     * @param string $userEmail
     * @param bool   $mustBeUnique
     *
     * @return CreateOrganisationRequestInterface
     *
     * @throws NoSuchEntityException
     */
    public function build(
        string $companyId,
        string $userEmail = '',
        bool $mustBeUnique = false
    ): CreateOrganisationRequestInterface {
        $gatewayRequest = $this->createOrganisationGatewayRequestFactory->create();
        return $gatewayRequest
            ->setCompanyId($companyId)
            ->setUniqueId('mage-org-' . hash('md5', $this->storeManager->getStore()->getCode() .
                $this->storeManager->getStore()->getName() . $userEmail . $companyId . ($mustBeUnique ? time() : '')))
            ->setRegistered(date('Y-m-d\TH:i:s\Z'));
    }
}
