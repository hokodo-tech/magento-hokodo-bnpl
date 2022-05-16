<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Observer;

use Hokodo\BNPL\Api\PaymentQuoteRepositoryInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

/**
 * Class Hokodo\BNPL\Observer\CartSaveBeforeObserver.
 */
class CartSaveBeforeObserver implements ObserverInterface
{
    /**
     * @var PaymentQuoteRepositoryInterface
     */
    private $paymentQuoteRepository;

    /**
     * @param PaymentQuoteRepositoryInterface $paymentQuoteRepository
     */
    public function __construct(PaymentQuoteRepositoryInterface $paymentQuoteRepository)
    {
        $this->paymentQuoteRepository = $paymentQuoteRepository;
    }

    /**
     * @inheritDoc
     *
     * @see \Magento\Framework\Event\ObserverInterface::execute()
     */
    public function execute(Observer $observer)
    {
        /** @var \Magento\Quote\Api\Data\CartInterface $quote */
        $quote = $observer->getEvent()->getCart()->getQuote();

        $paymentQuote = $this->getPaymentQuote($quote->getId());
        if ($paymentQuote && $paymentQuote->getId()) {
            $paymentQuote->setOrderId(null);
            $paymentQuote->setOfferId(null);
            $this->paymentQuoteRepository->save($paymentQuote);
        }
    }

    /**
     * A function that gets by quote id.
     *
     * @param int $quoteId
     *
     * @return \Hokodo\BNPL\Api\Data\PaymentQuoteInterface|null
     */
    private function getPaymentQuote($quoteId)
    {
        try {
            /*
             * @var \Hokodo\BNPL\Api\Data\PaymentQuoteInterface $paymentQuote
             */
            return $this->paymentQuoteRepository->getByQuoteId($quoteId);
        } catch (\Exception $e) {
            return null;
        }
    }
}
