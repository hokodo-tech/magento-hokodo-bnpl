<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Hokodo\BNPL\CustomerData;

use Hokodo\BNPL\Api\HokodoQuoteRepositoryInterface;
use Hokodo\BNPL\Gateway\Service\Order;
use Hokodo\BNPL\Model\RequestBuilder\OrderBuilder;
use Magento\Checkout\Model\Session;
use Magento\Customer\CustomerData\SectionSourceInterface;

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
     * @var OrderBuilder
     */
    private OrderBuilder $orderBuilder;

    /**
     * @var Order
     */
    private Order $order;

    /**
     * @param Session                        $checkoutSession
     * @param HokodoQuoteRepositoryInterface $hokodoQuoteRepository
     * @param OrderBuilder                   $orderBuilder
     * @param Order                          $order
     */
    public function __construct(
        Session $checkoutSession,
        HokodoQuoteRepositoryInterface $hokodoQuoteRepository,
        OrderBuilder $orderBuilder,
        Order $order
    ) {
        $this->checkoutSession = $checkoutSession;
        $this->hokodoQuoteRepository = $hokodoQuoteRepository;
        $this->orderBuilder = $orderBuilder;
        $this->order = $order;
    }

    /**
     * @inheritDoc
     */
    public function getSectionData()
    {
        $hokodoQuote = $this->hokodoQuoteRepository->getByQuoteId($this->checkoutSession->getQuoteId());
        if ($hokodoQuote->getPatchRequired() !== null) {
            $hokodoQuote->setOfferId('');
        }

        return [
            self::USER_ID => $hokodoQuote->getUserId() ?? '',
            self::ORGANISATION_ID => $hokodoQuote->getOrganisationId() ?? '',
            self::OFFER => '', //TODO GET OFFER ENDPOINT
        ];
    }
}
