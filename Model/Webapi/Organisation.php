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
use Hokodo\BNPL\Api\HokodoQuoteRepositoryInterface;
use Hokodo\BNPL\Api\Webapi\OrganisationInterface;
use Hokodo\BNPL\Gateway\Service\Organisation as OrganisationService;
use Magento\Checkout\Model\Session\Proxy;
use Magento\Store\Model\StoreManagerInterface;

class Organisation implements OrganisationInterface
{
    /**
     * @var OrganisationService
     */
    private OrganisationService $organisationService;

    /**
     * @var CreateOrganisationRequestInterfaceFactory
     */
    private CreateOrganisationRequestInterfaceFactory $createOrganisationGatewayRequestFactory;

    /**
     * @var StoreManagerInterface
     */
    private StoreManagerInterface $storeManager;

    /**
     * @var CreateOrganisationResponseInterfaceFactory
     */
    private CreateOrganisationResponseInterfaceFactory $createOrganisationResponseFactory;

    /**
     * @var Proxy
     */
    private Proxy $checkoutSession;

    /**
     * @var HokodoQuoteRepositoryInterface
     */
    private HokodoQuoteRepositoryInterface $hokodoQuoteRepository;

    /**
     * Organisation constructor.
     *
     * @param OrganisationService                        $organisationService
     * @param CreateOrganisationRequestInterfaceFactory  $createOrganisationGatewayRequestFactory
     * @param StoreManagerInterface                      $storeManager
     * @param CreateOrganisationResponseInterfaceFactory $createOrganisationResponseFactory
     * @param Proxy                                      $checkoutSession
     * @param HokodoQuoteRepositoryInterface             $hokodoQuoteRepository
     */
    public function __construct(
        OrganisationService $organisationService,
        CreateOrganisationRequestInterfaceFactory $createOrganisationGatewayRequestFactory,
        StoreManagerInterface $storeManager,
        CreateOrganisationResponseInterfaceFactory $createOrganisationResponseFactory,
        Proxy $checkoutSession,
        HokodoQuoteRepositoryInterface $hokodoQuoteRepository
    ) {
        $this->organisationService = $organisationService;
        $this->createOrganisationGatewayRequestFactory = $createOrganisationGatewayRequestFactory;
        $this->storeManager = $storeManager;
        $this->createOrganisationResponseFactory = $createOrganisationResponseFactory;
        $this->checkoutSession = $checkoutSession;
        $this->hokodoQuoteRepository = $hokodoQuoteRepository;
    }

    /**
     * Organisation create request webapi handler.
     *
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
                ->setUniqueId('mage-org-' . hash('md5', $this->storeManager->getStore()->getCode() .
                        $this->storeManager->getStore()->getName() . $payload->getCompanyId()))
                ->setRegistered(date('Y-m-d\TH:i:s\Z'));
            $organisation = $this->organisationService->createOrganisation($gatewayRequest);
            if ($dataModel = $organisation->getDataModel()) {
                $hokodoQuote = $this->hokodoQuoteRepository->getByQuoteId($this->checkoutSession->getQuoteId());
                if (!$hokodoQuote->getQuoteId()) {
                    $hokodoQuote->setQuoteId((int) $this->checkoutSession->getQuoteId());
                }
                $hokodoQuote->setOrganisationId($dataModel->getId());
                $this->hokodoQuoteRepository->save($hokodoQuote);
                $result->setId($dataModel->getId());
            }
        } catch (\Exception $e) {
            //TODO add error resolving
            $result->setId('');
        }
        return $result;
    }
}
