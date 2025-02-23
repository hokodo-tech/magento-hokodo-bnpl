<?php

/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Hokodo\BNPL\Model;

use Hokodo\BNPL\Api\CompanyCreditServiceInterface;
use Hokodo\BNPL\Api\Data\Company\CreditInterface;
use Hokodo\BNPL\Api\Data\Company\CreditLimitInterface;
use Hokodo\BNPL\Api\Data\Gateway\CompanyCreditRequestInterface;
use Hokodo\BNPL\Api\Data\Gateway\CompanyCreditRequestInterfaceFactory;
use Hokodo\BNPL\Gateway\Service\Company;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;

class CompanyCreditService implements CompanyCreditServiceInterface
{
    /**
     * @var Company
     */
    private Company $gateway;

    /**
     * @var CompanyCreditRequestInterfaceFactory
     */
    private CompanyCreditRequestInterfaceFactory $companyCreditRequestFactory;

    /**
     * @var StoreManagerInterface
     */
    private StoreManagerInterface $storeManager;

    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * CompanyCreditService constructor.
     *
     * @param Company                              $gateway
     * @param CompanyCreditRequestInterfaceFactory $companyCreditRequestFactory
     * @param StoreManagerInterface                $storeManager
     * @param LoggerInterface                      $logger
     */
    public function __construct(
        Company $gateway,
        CompanyCreditRequestInterfaceFactory $companyCreditRequestFactory,
        StoreManagerInterface $storeManager,
        LoggerInterface $logger
    ) {
        $this->gateway = $gateway;
        $this->companyCreditRequestFactory = $companyCreditRequestFactory;
        $this->storeManager = $storeManager;
        $this->logger = $logger;
    }

    /**
     * @inheritDoc
     */
    public function getCredit(string $companyId): ?CreditInterface
    {
        /** @var CompanyCreditRequestInterface $searchRequest */
        $searchRequest = $this->companyCreditRequestFactory->create();
        $searchRequest
            ->setCurrency($this->storeManager->getStore()->getCurrentCurrencyCode())
            ->setCompanyId($companyId);

        try {
            $companyCredit = $this->gateway->getCredit($searchRequest)->getDataModel();
            if ($companyCredit->getRejectionReason()) {
                $rejectionReasonDetails = $this->getRejectionReasonDetails($companyCredit->getRejectionReason());
                $companyCredit->setCreditLimit(
                    $companyCredit
                        ->getCreditLimit()
                        ->setAmount(0)
                        ->setAmountAvailable(0)
                        ->setAmountInUse(0)
                        ->setRejectionReason($rejectionReasonDetails)
                );
            }
            return $companyCredit;
        } catch (\Exception $e) {
            $data = [
                'message' => 'Hokodo_BNPL: company credit call failed with error.',
                'error' => $e->getMessage(),
            ];
            $this->logger->error(__METHOD__, $data);
        }

        return null;
    }

    /**
     * @inheritDoc
     */
    public function getCreditLimit(string $companyId): ?CreditLimitInterface
    {
        $creditLimit = $this->getCredit($companyId);
        return $creditLimit ? $creditLimit->getCreditLimit() : null;
    }

    /**
     * Get Rejection Reason Details As String.
     *
     * @param array $rejectionReason
     *
     * @return string|null
     */
    public function getRejectionReasonDetails(array $rejectionReason): ?string
    {
        $detail = null;
        if (!empty($rejectionReason['detail'])) {
            $detail = $rejectionReason['detail'];
        }

        if (!empty($rejectionReason['params']) && $detail) {
            foreach ($rejectionReason['params'] as $search => $replace) {
                $detail = str_replace('{' . $search . '}', $replace, $detail);
            }
        }
        return $detail;
    }
}
