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
use Hokodo\BNPL\Model\SaveLog as PaymentLogger;
use Magento\Quote\Model\QuoteIdMaskFactory;

/**
 * Class Hokodo\BNPL\Model\GuestSetOrganisationService.
 */
class GuestSetOrganisationService implements GuestSetOrganisationServiceInterface
{
    /**
     * @var SetOrganisationServiceInterface
     */
    private $setOrganisationService;

    /**
     * @var QuoteIdMaskFactory
     */
    private $quoteIdMaskFactory;

    /**
     * @var PaymentLogger
     */
    protected $paymentLogger;

    /**
     * @param SetOrganisationServiceInterface $setOrganisationService
     * @param QuoteIdMaskFactory              $quoteIdMaskFactory
     * @param PaymentLogger                   $paymentLogger
     */
    public function __construct(
        SetOrganisationServiceInterface $setOrganisationService,
        QuoteIdMaskFactory $quoteIdMaskFactory,
        PaymentLogger $paymentLogger
    ) {
        $this->setOrganisationService = $setOrganisationService;
        $this->quoteIdMaskFactory = $quoteIdMaskFactory;
        $this->paymentLogger = $paymentLogger;
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
        $this->paymentLogger->execute($data);
        $quoteIdMask = $this->quoteIdMaskFactory->create()->load($cartId, 'masked_id');
        return $this->setOrganisationService->setOrganisation($quoteIdMask->getQuoteId(), $organisation, $user);
    }
}
