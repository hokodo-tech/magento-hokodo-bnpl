<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Observer;

use Hokodo\BNPL\Api\Data\PaymentQuoteInterface;
use Hokodo\BNPL\Api\PaymentQuoteRepositoryInterface;
use Hokodo\BNPL\Model\SaveLog as Logger;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Quote\Model\Quote;
use Magento\Sales\Model\Order;

/**
 * Class Hokodo\BNPL\Observer\ProcessOrderPlaceObserver.
 */
class ProcessOrderPlaceObserver implements ObserverInterface
{
    /**
     * @var PaymentQuoteRepositoryInterface
     */
    private $paymentQuoteRepository;

    /**
     * @var Logger
     */
    private $logger;

    /**
     * @param PaymentQuoteRepositoryInterface $paymentQuoteRepository
     * @param Logger                          $logger
     */
    public function __construct(
        PaymentQuoteRepositoryInterface $paymentQuoteRepository,
        Logger $logger
    ) {
        $this->paymentQuoteRepository = $paymentQuoteRepository;
        $this->logger = $logger;
    }

    /**
     * A function that executes payments quote.
     *
     * @param Observer $observer
     *
     * @return ProcessOrderPlaceObserver
     *
     * @throws LocalizedException
     */
    public function execute(Observer $observer)
    {
        /**
         * @var Quote $quote
         */
        $quote = $observer->getEvent()->getQuote();
        $paymentQuote = $this->getPaymentQuote($quote->getId());
        if ($paymentQuote && $paymentQuote->getId()) {
            /**
             * @var Order $order
             */
            $order = $observer->getEvent()->getOrder();

            $order->setOrderApiId($paymentQuote->getOrderId());
            $order->setDeferredPaymentId($paymentQuote->getDeferredPaymentId());
        }

        return $this;
    }

    /**
     * A function that gets payment quote.
     *
     * @param int $quoteId
     *
     * @return PaymentQuoteInterface|null
     *
     * @throws LocalizedException
     */
    private function getPaymentQuote($quoteId)
    {
        try {
            return $this->paymentQuoteRepository->getByQuoteId($quoteId);
        } catch (\Exception $e) {
            $data = [
                'payment_log_content' => $e->getMessage(),
                'action_title' => 'ProcessOrderPlaceObserver:: Exception',
                'status' => 0,
                'quote_id' => $quoteId,
            ];
            $this->logger->execute($data);
            return null;
        }
    }
}
