<?php
/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Observer;

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

        if ($quote->getPayment()->getMethod() === \Hokodo\BNPL\Gateway\Config\Config::CODE &&
            ($additionalInformation = $quote->getPayment()->getAdditionalInformation())) {
            /**
             * @var Order $order
             */
            $order = $observer->getEvent()->getOrder();

            $order->setOrderApiId($additionalInformation['hokodo_order_id']);
            $order->setDeferredPaymentId($additionalInformation['hokodo_deferred_payment_id']);
        }

        return $this;
    }
}
