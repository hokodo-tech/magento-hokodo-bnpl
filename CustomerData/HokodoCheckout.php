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
use Magento\Checkout\Model\Session\Proxy;
use Magento\Customer\CustomerData\SectionSourceInterface;

class HokodoCheckout implements SectionSourceInterface
{
    public const USER_ID = 'userId';
    public const ORGANISATION_ID = 'organisationId';
    public const OFFER = 'offer';

    /**
     * @var Proxy
     */
    private Proxy $checkoutSession;

    /**
     * @var HokodoQuoteRepositoryInterface
     */
    private HokodoQuoteRepositoryInterface $hokodoQuoteRepository;
    private OrderBuilder $orderBuilder;
    private Order $order;

    public function __construct(
        Proxy $checkoutSession,
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
