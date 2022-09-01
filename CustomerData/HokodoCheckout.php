<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Hokodo\BNPL\CustomerData;

use Hokodo\BNPL\Api\HokodoQuoteRepositoryInterface;
use Hokodo\BNPL\Gateway\Service\Offer;
use Magento\Checkout\Model\Session;
use Magento\Customer\CustomerData\SectionSourceInterface;
use Magento\Framework\Exception\NotFoundException;
use Magento\Payment\Gateway\Command\CommandException;

class HokodoCheckout implements SectionSourceInterface
{
    public const USER_ID = 'userId';
    public const ORGANISATION_ID = 'organisationId';
    public const OFFER = 'offer';

    /**
     * @var Session
     */
    private Session $checkoutSession;

    /**
     * @var HokodoQuoteRepositoryInterface
     */
    private HokodoQuoteRepositoryInterface $hokodoQuoteRepository;

    /**
     * @var Offer
     */
    private Offer $offerService;

    /**
     * @param Session                        $checkoutSession
     * @param HokodoQuoteRepositoryInterface $hokodoQuoteRepository
     * @param Offer                          $offerService
     */
    public function __construct(
        Session $checkoutSession,
        HokodoQuoteRepositoryInterface $hokodoQuoteRepository,
        Offer $offerService
    ) {
        $this->checkoutSession = $checkoutSession;
        $this->hokodoQuoteRepository = $hokodoQuoteRepository;
        $this->offerService = $offerService;
    }

    /**
     * @inheritDoc
     */
    public function getSectionData()
    {
        $hokodoQuote = $this->hokodoQuoteRepository->getByQuoteId($this->checkoutSession->getQuoteId());
        $offer = '';
        if ($hokodoQuote->getPatchRequired() !== null) {
            $hokodoQuote->setOfferId('');
        } else {
            try {
                $offer = $this->offerService->getOffer(['id' => $hokodoQuote->getOfferId()])->getDataModel();
            } catch (NotFoundException|CommandException $e) {
                //TODO error logging
                $offer = '';
            }
        }

        return [
            self::USER_ID => $hokodoQuote->getUserId() ?? '',
            self::ORGANISATION_ID => $hokodoQuote->getOrganisationId() ?? '',
            self::OFFER => $offer ? $offer->__toArray() : '',
        ];
    }
}
