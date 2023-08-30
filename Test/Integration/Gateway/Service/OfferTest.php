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

// @codingStandardsIgnoreFile
class OfferTest extends AbstractService
{
    /**
     * @var array
     */
    protected $httpResponse = [
        'url' => 'https://api-sandbox.hokodo.co/v1/payment/offers/offr-vt7T2fjV8jqwTZXiDTVnwJ',
        'id' => 'offr-vt7T2fjV8jqwTZXiDTVnwJ',
        'order' => 'order-S2U9qPVaNsUtC89wwC2RAd',
        'offered_payment_plans' => [
            [
                'id' => 'ppln-ZeYUhpeeoi8wxrMqSi8aed',
                'name' => '30d',
                'template' => 'pptemp-f6pxeWjw47j2ATGyqCTZt3',
                'currency' => 'GBP',
                'protected_amount' => 16157,
                'unprotected_amount' => 0,
                'scheduled_payments' => [
                    [
                        'date' => '2023-08-29',
                        'amount' => 4847,
                        'customer_fee' => [
                            'percentage' => '0.00',
                            'amount' => 0,
                        ],
                        'allowed_payment_methods' => [
                            [
                                'type' => 'invoice',
                            ],
                            [
                                'type' => 'card',
                            ],
                        ],
                        'payment_method' => null,
                        'due_date_config' => [
                            'due_after_nb_days' => null,
                            'due_end_of_nb_months' => null,
                            'amount_percentage' => '30.00',
                            'is_upfront_payment' => true,
                        ],
                        'discounted_amount' => 4847,
                    ],
                    [
                        'date' => '2023-09-28',
                        'amount' => 11310,
                        'customer_fee' => [
                            'percentage' => '0.00',
                            'amount' => 0,
                        ],
                        'allowed_payment_methods' => [
                            [
                                'type' => 'invoice',
                            ],
                            [
                                'type' => 'direct_debit',
                            ],
                            [
                                'type' => 'card',
                            ],
                        ],
                        'payment_method' => null,
                        'due_date_config' => [
                            'due_after_nb_days' => 30,
                            'due_end_of_nb_months' => null,
                            'amount_percentage' => '70.00',
                            'is_upfront_payment' => false,
                        ],
                        'discounted_amount' => 11310,
                    ],
                ],
                'payment_terms_relative_to' => 'order_creation',
                'merchant_fee' => [
                    'currency' => 'GBP',
                    'amount' => 646,
                ],
                'customer_fee' => [
                    'percentage' => '0.00',
                    'currency' => 'GBP',
                    'amount' => 0,
                ],
                'customer_percentage_discount' => '0.00',
                'customer_discount' => [
                    'currency' => 'GBP',
                    'amount' => 0,
                ],
                'valid_until' => '2028-08-27T15:51:54.585Z',
                'payment_url' => '[hidden]',
                'status' => 'declined',
                'rejection_reason' => [
                    'code' => 'buyer-country',
                    'detail' => 'Nous ne sommes malheureusement pas en mesure d’assurer les acheteurs domiciliés dans le pays suivant : FR.',
                    'params' => [
                        'debtor_country' => 'FR',
                    ],
                ],
                'has_upfront_payment' => true,
            ],
            [
                'id' => 'ppln-3NwFSzLNrX9KozenFi4LVN',
                'name' => '60d',
                'template' => 'pptemp-PMLQyewA33vMvTdkbFUgVT',
                'currency' => 'GBP',
                'protected_amount' => 16157,
                'unprotected_amount' => 0,
                'scheduled_payments' => [
                    [
                        'date' => '2023-10-28',
                        'amount' => 16157,
                        'customer_fee' => [
                            'percentage' => '0.00',
                            'amount' => 0,
                        ],
                        'allowed_payment_methods' => [
                            [
                                'type' => 'invoice',
                            ],
                            [
                                'type' => 'direct_debit',
                            ],
                            [
                                'type' => 'card',
                            ],
                        ],
                        'payment_method' => null,
                        'due_date_config' => [
                            'due_after_nb_days' => 60,
                            'due_end_of_nb_months' => null,
                            'amount_percentage' => '100.00',
                            'is_upfront_payment' => false,
                        ],
                        'discounted_amount' => 16157,
                    ],
                ],
                'payment_terms_relative_to' => 'order_creation',
                'merchant_fee' => [
                    'currency' => 'GBP',
                    'amount' => 646,
                ],
                'customer_fee' => [
                    'percentage' => '0.00',
                    'currency' => 'GBP',
                    'amount' => 0,
                ],
                'customer_percentage_discount' => '0.00',
                'customer_discount' => [
                    'currency' => 'GBP',
                    'amount' => 0,
                ],
                'valid_until' => '2028-08-27T15:51:54.365Z',
                'payment_url' => '[hidden]',
                'status' => 'declined',
                'rejection_reason' => [
                    'code' => 'buyer-country',
                    'detail' => 'Nous ne sommes malheureusement pas en mesure d’assurer les acheteurs domiciliés dans le pays suivant : FR.',
                    'params' => [
                        'debtor_country' => 'FR',
                    ],
                ],
                'has_upfront_payment' => false,
            ],
        ],
        'legals' => [
            'type' => 'a',
            'terms_url' => 'https://static.hokodo.co/payments/b_v1.4/b_v1.4_fr.pdf',
        ],
        'urls' => [
            'success' => 'https://magento.hutr.dev/default/',
            'failure' => 'https://magento.hutr.dev/default/',
            'cancel' => 'https://magento.hutr.dev/default/',
            'notification' => 'https://magento.hutr.dev/default/rest/default/V1/deferredpayment/update',
            'merchant_terms' => 'https://magento.hutr.dev/default/',
        ],
        'locale' => 'en_US',
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
