<?php
/**
 * Copyright © 2018-2022 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Hokodo\BNPL\Plugin\Model\CheckoutAgreements;

use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\CheckoutAgreements\Model\AgreementsProvider as OriginalAgreementsProvider;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Psr\Log\LoggerInterface;

class AgreementsProvider
{
    /**
     * @var CheckoutSession
     */
    private CheckoutSession $checkoutSession;

    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * @param CheckoutSession $checkoutSession
     * @param LoggerInterface $logger
     */
    public function __construct(
        CheckoutSession $checkoutSession,
        LoggerInterface $logger
    ) {
        $this->checkoutSession = $checkoutSession;
        $this->logger = $logger;
    }

    /**
     * All Terms and Conditions are unnecessary.
     *
     * @param OriginalAgreementsProvider $subject
     * @param array                      $result
     *
     * @return array
     */
    public function afterGetRequiredAgreementIds(OriginalAgreementsProvider $subject, array $result): array
    {
        try {
            if ($this->checkoutSession->getQuote()->getPayment()->getMethod() == 'hokodo_bnpl') {
                return [];
            }
        } catch (LocalizedException|NoSuchEntityException $e) {
            $this->logger->error(__('Hokodo_BNPL: payment method is not defined  - %1', $e->getMessage()));
        }
        return $result;
    }
}
