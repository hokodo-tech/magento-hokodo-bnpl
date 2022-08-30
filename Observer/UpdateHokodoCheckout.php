<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Hokodo\BNPL\Observer;

use Hokodo\BNPL\Api\Data\HokodoQuoteInterface;
use Hokodo\BNPL\Api\HokodoQuoteRepositoryInterface;
use Magento\Checkout\Model\Session\Proxy;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class UpdateHokodoCheckout implements ObserverInterface
{
    /**
     * @var Proxy
     */
    private Proxy $checkout;

    /**
     * @var HokodoQuoteRepositoryInterface
     */
    private HokodoQuoteRepositoryInterface $hokodoQuoteRepository;

    public function __construct(
        Proxy $checkout,
        HokodoQuoteRepositoryInterface $hokodoQuoteRepository
    ) {
        $this->checkout = $checkout;
        $this->hokodoQuoteRepository = $hokodoQuoteRepository;
    }

    /**
     * @inheritDoc
     */
    public function execute(Observer $observer)
    {
        $hokodoQuote = $this->hokodoQuoteRepository->getByQuoteId($this->checkout->getQuoteId());
        $hokodoQuote
            ->setOfferId('')
            ->setPatchRequired(HokodoQuoteInterface::PATCH_ITEMS);
        $this->hokodoQuoteRepository->save($hokodoQuote);
    }
}
