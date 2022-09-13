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
     * @param Session                        $checkoutSession
     * @param HokodoQuoteRepositoryInterface $hokodoQuoteRepository
     * @param Offer                          $offerService
     * @param LoggerInterface                $logger
     */
    public function __construct(
        Session $checkoutSession,
        HokodoQuoteRepositoryInterface $hokodoQuoteRepository,
        Offer $offerService,
        LoggerInterface $logger
    ) {
        $this->checkoutSession = $checkoutSession;
        $this->hokodoQuoteRepository = $hokodoQuoteRepository;
        $this->offerService = $offerService;
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
            } catch (NotFoundException|CommandException $e) {
                $this->logger->error(__('Hokodo_BNPL: getOffer call failed with error - %1', $e->getMessage()));
            }
        }
        $this->hokodoQuoteRepository->save($hokodoQuote);

        return [
            self::OFFER => $offer ? $offer->__toArray() : '',
        ];
    }
}
