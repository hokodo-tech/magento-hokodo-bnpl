<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Observer;

use Hokodo\BNPL\Gateway\Service\Offer as OfferGatewayService;
use Hokodo\BNPL\Gateway\Service\Order;
use Hokodo\BNPL\Service\PaymentTerms;
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
    public const HOKODO_PAYMENT_PLAN_NAME = 'name';
    public const HOKODO_PAYMENT_TERMS_RELATIVE_TO = 'payment_terms_relative_to';
    public const HOKODO_PAYMENT_TERMS = 'payment_terms';
    public const HOKODO_PAYMENT_PLAN_DUE_DATE = 'due_date';
    public const HOKODO_PAYMENT_METHOD = 'payment_method';

    /**
     * @var array
     */
    private array $additionalInformationList = [
        self::HOKODO_USER_ID,
        self::HOKODO_ORGANISATION_ID,
        self::HOKODO_ORDER_ID,
        self::HOKODO_PAYMENT_OFFER_ID,
        self::HOKODO_DEFERRED_PAYMENT_ID,
    ];

    /**
     * @var Order
     */
    private Order $orderService;

    /**
     * @var OfferGatewayService
     */
    private OfferGatewayService $offerGatewayService;

    /**
     * @var PaymentTerms
     */
    private PaymentTerms $paymentTerms;

    /**
     * @param Order               $orderService
     * @param PaymentTerms        $paymentTerms
     * @param OfferGatewayService $offerGatewayService
     */
    public function __construct(
        Order $orderService,
        PaymentTerms $paymentTerms,
        OfferGatewayService $offerGatewayService
    ) {
        $this->orderService = $orderService;
        $this->paymentTerms = $paymentTerms;
        $this->offerGatewayService = $offerGatewayService;
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
                $hokodoOffer = $this->offerGatewayService
                    ->getOffer(['id' => $hokodoOrder->getPaymentOffer()])
                    ->getDataModel();
                foreach ($hokodoOffer->getOfferedPaymentPlans() as $paymentPlan) {
                    if ($paymentPlan->getStatus() != \Hokodo\BNPL\Api\Data\DeferredPaymentInterface::STATUS_ACCEPTED) {
                        continue;
                    }
                    $additionalData[self::HOKODO_PAYMENT_PLAN_NAME] = $paymentPlan->getName();
                    $additionalData[self::HOKODO_PAYMENT_TERMS_RELATIVE_TO] = $paymentPlan->getPaymentTermsRelativeTo();
                    $additionalData[self::HOKODO_PAYMENT_TERMS] = $this->paymentTerms
                        ->getPaymentTerms($paymentPlan->getName(), $paymentPlan->getPaymentTermsRelativeTo());

                    $scheduledPayments = $paymentPlan->getScheduledPayments();
                    foreach ($scheduledPayments as $scheduledPayment) {
                        list($year, $month, $day) = explode('-', $scheduledPayment->getDate());
                        $date = $day . '/' . $month . '/' . $year;
                        $additionalData[self::HOKODO_PAYMENT_PLAN_DUE_DATE] = $date;
                        $paymentMethod = $scheduledPayment->getPaymentMethod()->getType();
                        $additionalData[self::HOKODO_PAYMENT_METHOD] = $paymentMethod;
                    }
                }
                $additionalData['hokodo_deferred_payment_id'] = $hokodoOrder->getDeferredPayment();

                $quote->getPayment()->setAdditionalInformation($additionalData)->save();
            }
        }
    }
}
