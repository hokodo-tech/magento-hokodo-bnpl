<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Hokodo\BNPL\Test\Integration\Gateway\Service;

use Hokodo\BNPL\Api\Data\Gateway\CreateOfferRequestInterface;
use Hokodo\BNPL\Api\Data\Gateway\OfferUrlsInterface;
use Hokodo\BNPL\Api\Data\PaymentOffersInterface;

class OfferTest extends AbstractService
{
    /**
     * @var array
     */
    protected $httpResponse = [
        'url' => 'https://api-sandbox.hokodo.co/v1/payment/offers/offer-EzH6BixNSHvaByEpXcFW5h',
        'id' => 'offer-bqRyHAGaFrDEN8JNjXJirp',
        'order' => 'order-dTHo9Q2idffT7CMPCVjztA',
        'offered_payment_plans' => [
            [
                'id' => 'ppln-bqRyHAGaFrDEN8JNjXJirp',
                'name' => 'net30',
                'template' => 'pptemp-RySHMoFqSxfmkcfrYC2VeF',
                'currency' => 'GBP',
                'protected_amount' => 100000,
                'unprotected_amount' => 0,
                'scheduled_payments' => [
                    [
                        'date' => '2020-10-16',
                        'amount' => 100000,
                        'discounted_amount' => 100000,
                        'customer_fee' => [
                            'percentage' => '0.00',
                            'amount' => 0,
                        ],
                        'allowed_payment_methods' => [
                            [
                                'type' => 'card',
                            ],
                            [
                                'type' => 'direct_debit',
                            ],
                            [
                                'type' => 'invoice',
                            ],
                        ],
                        'payment_method' => [
                            'type' => 'card',
                        ],
                        'due_date_config' => [
                            'due_after_nb_days' => 30,
                            'due_end_of_nb_months' => null,
                            'amount_percentage' => '100.0',
                            'is_upfront_payment' => false,
                        ],
                    ],
                ],
                'payment_terms_relative_to' => 'order_creation',
                'merchant_fee' => [
                    'currency' => 'GBP',
                    'amount' => 1000,
                ],
                'customer_fee' => [
                    'currency' => 'GBP',
                    'percentage' => '0.00',
                    'amount' => 0,
                ],
                'customer_percentage_discount' => '0.00',
                'customer_discount' => [
                    'currency' => 'EUR',
                    'amount' => 0,
                ],
                'valid_until' => '2020-09-16T12:44:49.841Z',
                'payment_url' => 'https://payment.app.hokodo.co/plans/ppln-bqRyHAGaFrDEN8JNjXJirp',
                'status' => 'offered',
                'rejection_reason' => null,
                'has_upfront_payment' => false,
            ],
            [
                'id' => 'ppln-bqRyHAGaFrDEN8JNjXJirp',
                'name' => 'net30',
                'template' => 'pptemp-RySHMoFqSxfmkcfrYC2VeF',
                'currency' => 'GBP',
                'protected_amount' => 100000,
                'unprotected_amount' => 0,
                'scheduled_payments' => [
                    [
                        'date' => '2020-10-16',
                        'amount' => 100000,
                        'discounted_amount' => 100000,
                        'customer_fee' => [
                            'percentage' => '0.00',
                            'amount' => 0,
                        ],
                        'allowed_payment_methods' => [
                            [
                                'type' => 'card',
                            ],
                            [
                                'type' => 'direct_debit',
                            ],
                            [
                                'type' => 'invoice',
                            ],
                        ],
                        'payment_method' => [
                            'type' => 'card',
                        ],
                        'due_date_config' => [
                            'due_after_nb_days' => 30,
                            'due_end_of_nb_months' => null,
                            'amount_percentage' => '100.0',
                            'is_upfront_payment' => false,
                        ],
                    ],
                ],
                'payment_terms_relative_to' => 'order_creation',
                'merchant_fee' => [
                    'currency' => 'GBP',
                    'amount' => 1000,
                ],
                'customer_fee' => [
                    'currency' => 'GBP',
                    'percentage' => '0.00',
                    'amount' => 0,
                ],
                'customer_percentage_discount' => '0.00',
                'customer_discount' => [
                    'currency' => 'EUR',
                    'amount' => 0,
                ],
                'valid_until' => '2020-09-16T12:44:49.841Z',
                'payment_url' => 'https://payment.app.hokodo.co/plans/ppln-bqRyHAGaFrDEN8JNjXJirp',
                'status' => 'expired',
                'rejection_reason' => null,
                'has_upfront_payment' => false,
            ],
        ],
        'urls' => [
            'success' => 'https://merchant.com/payment/ok',
            'failure' => 'https://merchant.com/checkout',
            'cancel' => 'https://merchant.com/checkout',
            'notification' => 'https://backend.merchant.com/payment/notifications',
            'merchant_terms' => 'https://merchant.com/terms',
        ],
        'locale' => 'en-gb',
        'metadata' => [
        ],
    ];

    public function testCreate(): void
    {
        $offerService = $this->objectManager->get(\Hokodo\BNPL\Gateway\Service\Offer::class);
        $offerUrls = $this->objectManager->get(OfferUrlsInterface::class)
            ->setCancelUrl('https://test.com')
            ->setFailureUrl('https://test.com')
            ->setMerchantTermsUrl('https://test.com')
            ->setNotificationUrl('https://test.com')
            ->setSuccessUrl('https://test.com');
        $createRequest = $this->objectManager->get(CreateOfferRequestInterface::class)
            ->setLocale('en_uk')
            ->setMetadata([])
            ->setOrder('test-order')
            ->setUrls($offerUrls);

        $result = $offerService->createOffer($createRequest);
        $this->assertInstanceOf(PaymentOffersInterface::class, $result->getDataModel());
    }

    public function testGet(): void
    {
        $offerService = $this->objectManager->get(\Hokodo\BNPL\Gateway\Service\Offer::class);

        $result = $offerService->getOffer(['id' => 'test-offer']);
        $offer = $result->getDataModel();
        $this->assertInstanceOf(PaymentOffersInterface::class, $offer);
        $this->assertCount(count($this->httpResponse['offered_payment_plans']), $offer->getOfferedPaymentPlans());
        $paymentPlan = $offer->getOfferedPaymentPlans()[1];
        $this->assertEquals(
            $this->httpResponse['offered_payment_plans'][1]['status'],
            $paymentPlan->getStatus()
        );
    }
}
