<?php

/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Hokodo\BNPL\Observer;

use Hokodo\BNPL\Api\Data\HokodoQuoteInterface;
use Hokodo\BNPL\Api\HokodoQuoteRepositoryInterface;
use Magento\Checkout\Model\Session;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class UpdateHokodoCheckout implements ObserverInterface
{
    /**
     * @var Session
     */
    private Session $checkout;

    /**
     * @var HokodoQuoteRepositoryInterface
     */
    private HokodoQuoteRepositoryInterface $hokodoQuoteRepository;

    /**
     * @param Session                        $checkout
     * @param HokodoQuoteRepositoryInterface $hokodoQuoteRepository
     */
    public function __construct(
        Session $checkout,
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
        if ($quoteId = $this->checkout->getQuoteId()) {
            $hokodoQuote = $this->hokodoQuoteRepository->getByQuoteId($quoteId);
            if ($hokodoQuote->getQuoteId()) {
                $hokodoQuote
                    ->setOfferId('')
                    ->setPatchType(HokodoQuoteInterface::PATCH_ITEMS);
                $this->hokodoQuoteRepository->save($hokodoQuote);
            }
        }
    }
}
