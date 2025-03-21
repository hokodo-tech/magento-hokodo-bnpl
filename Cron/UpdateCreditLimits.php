<?php

/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Hokodo\BNPL\Cron;

use Hokodo\BNPL\Api\CompanyCreditServiceInterface;
use Hokodo\BNPL\Api\Data\HokodoCustomerInterface;
use Hokodo\BNPL\Api\Data\HokodoEntityInterface;
use Hokodo\BNPL\Gateway\Config\Config;
use Hokodo\BNPL\Model\HokodoCompanyProvider;
use Magento\Framework\Api\SearchCriteriaBuilderFactory;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Store\Model\App\Emulation;
use Psr\Log\LoggerInterface;

class UpdateCreditLimits
{
    /**
     * @var HokodoCompanyProvider
     */
    private HokodoCompanyProvider $hokodoCompanyProvider;

    /**
     * @var SearchCriteriaBuilderFactory
     */
    private SearchCriteriaBuilderFactory $criteriaBuilderFactory;

    /**
     * @var CompanyCreditServiceInterface
     */
    private CompanyCreditServiceInterface $companyCreditService;

    /**
     * @var Config
     */
    private Config $config;

    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * @var Emulation
     */
    private Emulation $appEmulation;

    /**
     * @param HokodoCompanyProvider         $hokodoCompanyProvider
     * @param SearchCriteriaBuilderFactory  $criteriaBuilderFactory
     * @param CompanyCreditServiceInterface $companyCreditService
     * @param Config                        $config
     * @param Emulation                     $appEmulation
     * @param LoggerInterface               $logger
     */
    public function __construct(
        HokodoCompanyProvider $hokodoCompanyProvider,
        SearchCriteriaBuilderFactory $criteriaBuilderFactory,
        CompanyCreditServiceInterface $companyCreditService,
        Config $config,
        Emulation $appEmulation,
        LoggerInterface $logger
    ) {
        $this->hokodoCompanyProvider = $hokodoCompanyProvider;
        $this->criteriaBuilderFactory = $criteriaBuilderFactory;
        $this->companyCreditService = $companyCreditService;
        $this->config = $config;
        $this->logger = $logger;
        $this->appEmulation = $appEmulation;
    }

    /**
     * Update Credit Limit.
     *
     * @return void
     */
    public function execute()
    {
        $this->logger->debug('hokodo_bnpl_update_credit_limits: start');
        if (!$this->config->getValue(Config::IS_CRON_ENABLED)) {
            $this->logger->debug('hokodo_bnpl_update_credit_limits: disabled');
            return;
        }
        $searchCriteriaBuilder = $this->criteriaBuilderFactory->create();
        $searchCriteriaBuilder->addFilter(HokodoCustomerInterface::COMPANY_ID, null, 'neq');
        $result = $this->hokodoCompanyProvider->getEntityRepository()->getList($searchCriteriaBuilder->create());
        $this->logger->debug('hokodo_bnpl_update_credit_limits: total count:' . $result->getTotalCount());
        /** @var HokodoEntityInterface $hokodoEntity */
        foreach ($result->getItems() as $hokodoEntity) {
            if (!$hokodoEntity->getExtensionAttributes()) {
                $this->hokodoCompanyProvider->getEntityRepository()->delete($hokodoEntity);
                continue;
            }
            $this->appEmulation->startEnvironmentEmulation($hokodoEntity->getExtensionAttributes()->getStoreId());
            //Adding pause to prevent rate limit hit
            usleep(1000000);
            $creditLimit = $this->companyCreditService
                ->getCreditLimit($hokodoEntity[HokodoCustomerInterface::COMPANY_ID]);
            if ($creditLimit) {
                $hokodoEntity->setCreditLimit($creditLimit);
                try {
                    $this->hokodoCompanyProvider->getEntityRepository()->save($hokodoEntity);
                } catch (CouldNotSaveException $e) {
                    $this->logger->debug('hokodo_bnpl_update_credit_limits: error');
                    $this->logger->error($e->getMessage());
                }
            }
            $this->appEmulation->stopEnvironmentEmulation();
        }
        $this->logger->debug('hokodo_bnpl_update_credit_limits: end');
    }
}
