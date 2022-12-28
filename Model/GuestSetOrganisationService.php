<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Model;

use Hokodo\BNPL\Api\Data\HokodoOrganisationInterface;
use Hokodo\BNPL\Api\Data\UserInterface;
use Hokodo\BNPL\Api\GuestSetOrganisationServiceInterface;
use Hokodo\BNPL\Api\SetOrganisationServiceInterface;
use Magento\Quote\Model\QuoteIdMaskFactory;
use Psr\Log\LoggerInterface as Logger;

/**
 * Class Hokodo\BNPL\Model\GuestSetOrganisationService.
 */
class GuestSetOrganisationService implements GuestSetOrganisationServiceInterface
{
    /**
     * @var SetOrganisationServiceInterface
     */
    private SetOrganisationServiceInterface $setOrganisationService;

    /**
     * @var QuoteIdMaskFactory
     */
    private QuoteIdMaskFactory $quoteIdMaskFactory;

    /**
     * @var Logger
     */
    protected Logger $logger;

    /**
     * @param SetOrganisationServiceInterface $setOrganisationService
     * @param QuoteIdMaskFactory              $quoteIdMaskFactory
     * @param Logger                          $logger
     */
    public function __construct(
        SetOrganisationServiceInterface $setOrganisationService,
        QuoteIdMaskFactory $quoteIdMaskFactory,
        Logger $logger
    ) {
        $this->setOrganisationService = $setOrganisationService;
        $this->quoteIdMaskFactory = $quoteIdMaskFactory;
        $this->logger = $logger;
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\GuestSetOrganisationServiceInterface::setOrganisation()
     */
    public function setOrganisation($cartId, HokodoOrganisationInterface $organisation, UserInterface $user)
    {
        $log = '****************** ' . $user->getId() . "\t";
        $log .= $user->getEmail() . "\t" . get_class($user) . "\t";
        $log .= get_class($organisation) . "\t" . $cartId;
        $data = [
            'payment_log_content' => $log,
            'action_title' => 'GuestSetOrganisationService::setOrganisation()',
            'status' => 1,
        ];
        $this->logger->debug(__METHOD__, $data);
        $quoteIdMask = $this->quoteIdMaskFactory->create()->load($cartId, 'masked_id');
        return $this->setOrganisationService->setOrganisation($quoteIdMask->getQuoteId(), $organisation, $user);
    }
}
