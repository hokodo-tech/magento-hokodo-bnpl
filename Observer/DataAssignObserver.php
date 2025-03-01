<?php

/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Observer;

use Hokodo\BNPL\Api\Data\PaymentPlanInterface;
use Hokodo\BNPL\Api\Data\ScheduledPaymentsInterface;
use Hokodo\BNPL\Gateway\Service\DeferredPayment;
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
    public const HOKODO_DEFERRED_PAYMENT_NUMBER = 'hokodo_deferred_payment_number';
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
     * @var DeferredPayment
     */
    private DeferredPayment $deferredPayment;

    /**
     * @param Order               $orderService
     * @param PaymentTerms        $paymentTerms
     * @param OfferGatewayService $offerGatewayService
     * @param DeferredPayment     $deferredPayment
     */
    public function __construct(
        Order $orderService,
        PaymentTerms $paymentTerms,
        OfferGatewayService $offerGatewayService,
        DeferredPayment $deferredPayment
    ) {
        $this->orderService = $orderService;
        $this->paymentTerms = $paymentTerms;
        $this->offerGatewayService = $offerGatewayService;
        $this->deferredPayment = $deferredPayment;
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
                    if ($paymentPlan->getStatus() == \Hokodo\BNPL\Api\Data\DeferredPaymentInterface::STATUS_ACCEPTED) {
                        $additionalData = $this->addDataFromPaymentPlan($paymentPlan, $additionalData);
                    }
                }

                $additionalData[self::HOKODO_DEFERRED_PAYMENT_ID] = $hokodoOrder->getDeferredPayment();
                if (!empty($additionalData[self::HOKODO_DEFERRED_PAYMENT_ID])) {
                    $deferredPayment = $this->deferredPayment->getDeferredPayment(
                        ['deferredpayment_id' => $additionalData[self::HOKODO_DEFERRED_PAYMENT_ID]]
                    )->getDataModel();
                    $additionalData[self::HOKODO_DEFERRED_PAYMENT_NUMBER] = $deferredPayment->getNumber();
                }
                $quote->getPayment()->setAdditionalInformation($additionalData)->save();
            }
        }
    }

    /**
     * Add data from Payment Plan.
     *
     * @param PaymentPlanInterface $paymentPlan
     * @param array                $data
     *
     * @return array
     */
    private function addDataFromPaymentPlan(PaymentPlanInterface $paymentPlan, array $data): array
    {
        $data[self::HOKODO_PAYMENT_PLAN_NAME] = $paymentPlan->getName();
        $data[self::HOKODO_PAYMENT_TERMS_RELATIVE_TO] = $paymentPlan->getPaymentTermsRelativeTo();
        $data[self::HOKODO_PAYMENT_TERMS] = $this->paymentTerms
            ->getPaymentTerms($paymentPlan->getName(), $paymentPlan->getPaymentTermsRelativeTo());

        $scheduledPayments = $paymentPlan->getScheduledPayments();
        foreach ($scheduledPayments as $scheduledPayment) {
            $data = $this->addDataFromScheduledPayment($scheduledPayment, $data);
        }
        return $data;
    }

    /**
     * Add data from Scheduled Payment.
     *
     * @param ScheduledPaymentsInterface $scheduledPayment
     * @param array                      $data
     *
     * @return array
     */
    private function addDataFromScheduledPayment(ScheduledPaymentsInterface $scheduledPayment, array $data): array
    {
        if ($data[self::HOKODO_PAYMENT_TERMS_RELATIVE_TO] != PaymentTerms::PAYMENT_TERMS_RELATIVE_TO_FIRST_CAPTURE
            && $data[self::HOKODO_PAYMENT_TERMS_RELATIVE_TO] != PaymentTerms::PAYMENT_TERMS_RELATIVE_TO_EVERY_CAPTURE
        ) {
            list($year, $month, $day) = explode('-', $scheduledPayment->getDate());
            $date = $day . '/' . $month . '/' . $year;
            $data[self::HOKODO_PAYMENT_PLAN_DUE_DATE] = $date;
        }
        $paymentMethod = $scheduledPayment->getPaymentMethod()->getType();
        $data[self::HOKODO_PAYMENT_METHOD] = $paymentMethod;
        return $data;
    }
}
