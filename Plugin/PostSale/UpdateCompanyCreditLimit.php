<?php

/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Hokodo\BNPL\Plugin\PostSale;

use Hokodo\BNPL\Api\CompanyCreditServiceInterface;
use Hokodo\BNPL\Gateway\Command\PostSale\CapturePayment;
use Hokodo\BNPL\Gateway\Command\PostSale\RefundPayment;
use Hokodo\BNPL\Gateway\Command\PostSale\VoidPayment;
use Hokodo\BNPL\Model\HokodoCompanyProvider;
use Magento\Sales\Api\Data\OrderInterface;
use Psr\Log\LoggerInterface;

class UpdateCompanyCreditLimit
{
    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * @var CompanyCreditServiceInterface
     */
    private CompanyCreditServiceInterface $companyCreditService;
    /**
     * @var HokodoCompanyProvider
     */
    private HokodoCompanyProvider $hokodoCompanyProvider;

    /**
     * UpdateHokodoCompanyLimit constructor.
     *
     * @param LoggerInterface               $logger
     * @param CompanyCreditServiceInterface $companyCreditService
     * @param HokodoCompanyProvider         $hokodoCompanyProvider
     */
    public function __construct(
        LoggerInterface $logger,
        CompanyCreditServiceInterface $companyCreditService,
        HokodoCompanyProvider $hokodoCompanyProvider
    ) {
        $this->logger = $logger;
        $this->companyCreditService = $companyCreditService;
        $this->hokodoCompanyProvider = $hokodoCompanyProvider;
    }

    /**
     * Update company's credit limit after successful post-sale command.
     *
     * @param CapturePayment|RefundPayment|VoidPayment $subject
     * @param mixed                                    $result
     * @param array                                    $commandSubject
     *
     * @return mixed
     */
    public function afterExecute($subject, $result, array $commandSubject)
    {
        // Adding try/catch to avoid code failing on successful post sale event.
        try {
            /* @var OrderInterface $order */
            if ($customerId = $commandSubject['payment']->getOrder()->getCustomerId()) {
                $hokodoEntityRepository = $this->hokodoCompanyProvider->getEntityRepository();
                $hokodoCompanyEntity = $hokodoEntityRepository->getByCustomerId((int) $customerId);
                if ($hokodoCompanyEntity->getEntityId()) {
                    $hokodoCompanyEntity->setCreditLimit(
                        $this->companyCreditService->getCreditLimit($hokodoCompanyEntity->getCompanyId())
                    );
                    $hokodoEntityRepository->saveHokodoEntity($hokodoCompanyEntity);
                }
            }
        } catch (\Exception $e) {
            $data = [
                //@codingStandardsIgnoreLine
                'message' => 'Hokodo_BNPL: company credit update failed with error on ' . gettype($subject),
                'error' => $e->getMessage(),
            ];
            $this->logger->debug(__METHOD__, $data);
        }

        return $result;
    }
}
