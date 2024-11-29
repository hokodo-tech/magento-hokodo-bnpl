<?php

/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Hokodo\BNPL\Test\Integration\Gateway\Service;

class DeferredPaymentTest extends AbstractService
{
    /**
     * @var array
     */
    protected $httpResponse = [
        'url' => 'https://some.url/',
        'id' => 'defpay-bqRyGIGaORKiK8bhMVPirp',
        'number' => 'P-7WR2-PR0S',
        'payment_plan' => 'ppln-gJdDHAGaFrDLDvJNXJird',
        'order' => 'order-dTHo9Q2idffT7CMPCVjztA',
        'status' => 'accepted',
        'currency' => 'GBP',
        'authorisation' => 50000,
        'protected_captures' => 20000,
        'unprotected_captures' => 0,
        'refunds' => 10000,
        'voided_authorisation' => 15000,
        'expired_authorisation' => 5000,
        'repayment_info' => [
            'status' => 'paid',
            'outstanding_amount' => 10000,
            'currency' => 'GBP',
        ],
    ];

    public function testDeferredPaymentService(): void
    {
        $deferredPaymentService = $this->objectManager->get(\Hokodo\BNPL\Gateway\Service\DeferredPayment::class);

        $result = $deferredPaymentService->getDeferredPayment(
            ['deferredpayment_id' => '1']
        );

        $payment = $result->getDataModel();

        $this->assertInstanceOf(\Hokodo\BNPL\Api\Data\DeferredPaymentInterface::class, $payment);
        $this->assertEquals($this->httpResponse['status'], $payment->getStatus());
    }
}
