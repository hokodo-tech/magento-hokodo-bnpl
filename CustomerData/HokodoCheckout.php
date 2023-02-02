<?php
/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Hokodo\BNPL\CustomerData;

use Hokodo\BNPL\Api\Data\PaymentOffersInterface;
use Hokodo\BNPL\Api\Data\PaymentPlanInterface;
use Hokodo\BNPL\Api\HokodoQuoteRepositoryInterface;
use Hokodo\BNPL\Gateway\Service\Offer;
use Hokodo\BNPL\Model\Webapi\Offer as WebapiOffer;
use Magento\Checkout\Model\Session;
use Magento\Customer\CustomerData\SectionSourceInterface;
use Psr\Log\LoggerInterface;

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
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * @var WebapiOffer
     */
    private WebapiOffer $webapiOffer;

    /**
     * @param Session                        $checkoutSession
     * @param HokodoQuoteRepositoryInterface $hokodoQuoteRepository
     * @param Offer                          $offerService
     * @param WebapiOffer                    $webapiOffer
     * @param LoggerInterface                $logger
     */
    public function __construct(
        Session $checkoutSession,
        HokodoQuoteRepositoryInterface $hokodoQuoteRepository,
        Offer $offerService,
        WebapiOffer $webapiOffer,
        LoggerInterface $logger
    ) {
        $this->checkoutSession = $checkoutSession;
        $this->hokodoQuoteRepository = $hokodoQuoteRepository;
        $this->offerService = $offerService;
        $this->webapiOffer = $webapiOffer;
        $this->logger = $logger;
    }

    /**
     * @inheritDoc
     */
    public function getSectionData()
    {
        $hokodoQuote = $this->hokodoQuoteRepository->getByQuoteId($this->checkoutSession->getQuoteId());
        $offer = '';
        if ($hokodoQuote->getPatchType() !== null) {
            $hokodoQuote->setOfferId('');
        }
        if ($hokodoQuote->getOfferId()) {
            try {
                $offer = $this->offerService->getOffer(['id' => $hokodoQuote->getOfferId()])->getDataModel();
                $this->addIsEligibleFlag($offer);
            } catch (\Exception $e) {
                $data = [
                    'message' => 'Hokodo_BNPL: getOffer call failed with error.',
                    'error' => $e->getMessage(),
                ];
                $this->logger->error(__METHOD__, $data);
                $hokodoQuote->setOfferId('');
            }
        }
        $this->hokodoQuoteRepository->save($hokodoQuote);
        return [
            self::OFFER => $offer ? $offer->__toArray() : '',
        ];
    }

    /**
     * Add is_eligible flag to offer.
     *
     * @param PaymentOffersInterface $offer
     *
     * @return void
     */
    private function addIsEligibleFlag(PaymentOffersInterface $offer): void
    {
        $offer->setIsEligible(
            $this->webapiOffer->isPaymentPlanHaveStatus($offer, PaymentPlanInterface::STATUS_OFFERED)
        );
    }
}
