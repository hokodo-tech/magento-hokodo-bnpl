<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Observer;

use Hokodo\BNPL\Api\Data\PaymentQuoteInterface;
use Hokodo\BNPL\Api\PaymentQuoteRepositoryInterface;
use Hokodo\BNPL\Gateway\Service\Order;
use Hokodo\BNPL\Model\SaveLog as Logger;
use Magento\Framework\Event\Observer;
use Magento\Payment\Observer\AbstractDataAssignObserver;
use Magento\Quote\Api\Data\PaymentInterface;

/**
 * Class Hokodo\BNPL\Observer\DataAssignObserver.
 */
class DataAssignObserver extends AbstractDataAssignObserver
{
    public const HOKODO_USER_ID = 'hokodo_user_id';
    public const HOKODO_ORGANISATION_ID = 'hokodo_organisation_id';
    public const HOKODO_ORDER_ID = 'hokodo_order_id';
    public const HOKODO_PAYMENT_OFFER_ID = 'hokodo_payment_offer_id';
    public const HOKODO_DEFERRED_PAYMENT_ID = 'hokodo_deferred_payment_id';

    /**
     * @var array
     */
    private $additionalInformationList = [
        self::HOKODO_USER_ID,
        self::HOKODO_ORGANISATION_ID,
        self::HOKODO_ORDER_ID,
        self::HOKODO_PAYMENT_OFFER_ID,
        self::HOKODO_DEFERRED_PAYMENT_ID,
    ];

    /**
     * @var array
     */
    private $additionalInformationMap = [
        self::HOKODO_USER_ID => PaymentQuoteInterface::USER_ID,
        self::HOKODO_ORGANISATION_ID => PaymentQuoteInterface::ORGANISATION_ID,
        self::HOKODO_ORDER_ID => PaymentQuoteInterface::ORDER_ID,
        self::HOKODO_PAYMENT_OFFER_ID => PaymentQuoteInterface::OFFER_ID,
        self::HOKODO_DEFERRED_PAYMENT_ID => PaymentQuoteInterface::DEFERRED_PAYMENT_ID,
    ];

    /**
     * @var PaymentQuoteRepositoryInterface
     */
    private $paymentQuoteRepository;

    /**
     * @var Logger
     */
    private $logger;

    /**
     * @var Order
     */
    private $orderService;

    /**
     * @param PaymentQuoteRepositoryInterface $paymentQuoteRepository
     * @param Order                           $orderService
     * @param Logger                          $logger
     */
    public function __construct(
        PaymentQuoteRepositoryInterface $paymentQuoteRepository,
        Order $orderService,
        Logger $logger
    ) {
        $this->paymentQuoteRepository = $paymentQuoteRepository;
        $this->logger = $logger;
        $this->orderService = $orderService;
    }

    /**
     * @inheritDoc
     *
     * @see \Magento\Framework\Event\ObserverInterface::execute()
     */
    public function execute(Observer $observer)
    {
        $paymentInfo = $this->readPaymentModelArgument($observer);
        $quote = $paymentInfo->getQuote();
        if ($quote->getPayment()->getMethod() === \Hokodo\BNPL\Gateway\Config\Config::CODE) {
            $data = $this->readDataArgument($observer);
            $additionalData = $data->getData(PaymentInterface::KEY_ADDITIONAL_DATA);
            if (!is_array($additionalData)) {
                return;
            }

            foreach ($this->additionalInformationList as $additionalInformationKey) {
                if (isset($additionalData[$additionalInformationKey])) {
                    $paymentInfo->setAdditionalInformation(
                        $additionalInformationKey,
                        $additionalData[$additionalInformationKey]
                    );
                }
            }

            if (isset($additionalData['hokodo_order_id'])) {
                //TODO rebuild using DTO
                $hokodoOrder = $this->orderService->getOrder(
                    ['id' => $additionalData['hokodo_order_id']]
                )->getDataModel();
                $additionalData['hokodo_deferred_payment_id'] = $hokodoOrder->getDeferredPayment();
                $quote->getPayment()->setAdditionalInformation($additionalData)->save();
            }

            $paymentQuote = $this->getPaymentQuote($quote->getId());
            if ($paymentQuote && $paymentQuote->getId()) {
                foreach ($this->additionalInformationMap as $key => $map) {
                    if ($paymentInfo->getAdditionalInformation($key)) {
                        $paymentQuote->setData($map, $paymentInfo->getAdditionalInformation($key));
                    } else {
                        $paymentQuote->setData($map, null);
                    }
                }

                try {
                    $this->paymentQuoteRepository->save($paymentQuote);
                } catch (\Exception $e) {
                    $data = [
                        'payment_log_content' => $e->getMessage(),
                        'action_title' => 'DataAssignObserver::execute Exception',
                        'status' => 0,
                        'quote_id' => $quote->getId(),
                    ];
                    $this->logger->execute($data);
                    return;
                }
            }
        }
    }

    /**
     * A function that catch exception.
     *
     * @param int $quoteId
     *
     * @return \Hokodo\BNPL\Api\Data\PaymentQuoteInterface|null
     */
    private function getPaymentQuote($quoteId)
    {
        try {
            return $this->paymentQuoteRepository->getByQuoteId($quoteId);
        } catch (\Exception $e) {
            $data = [
                'payment_log_content' => $e->getMessage(),
                'action_title' => 'DataAssignObserver::getPaymentQuote Exception',
                'status' => 0,
                'quote_id' => $quoteId,
            ];
            $this->logger->execute($data);
            return null;
        }
    }
}
