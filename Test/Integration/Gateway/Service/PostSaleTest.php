<?php

/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Hokodo\BNPL\Test\Integration\Gateway\Service;

use Hokodo\BNPL\Api\Data\Gateway\DeferredPaymentsPostSaleActionInterface;
use Hokodo\BNPL\Gateway\Service\PostSale;

class PostSaleTest extends AbstractService
{
    /**
     * @var array
     */
    protected $httpResponse = [
        'created' => '2020-10-15T19:53:42.345Z',
        'type' => 'capture',
        'amount' => 5000,
        'currency' => 'GBP',
        'metadata' => [
            'reference' => "Bob's burger patties",
            'supplier' => 'Krusty Krabs Factory',
        ],
        'changes' => [
            'authorisation' => -5000,
            'protected_captures' => 5000,
            'unprotected_captures' => 0,
            'refunds' => 0,
            'voided_authorisation' => 0,
            'expired_authorisation' => 0,
        ],
    ];

    /**
     * @return void
     *
     * @throws \Magento\Framework\Exception\NotFoundException
     * @throws \Magento\Payment\Gateway\Command\CommandException
     */
    public function testEvents()
    {
        $postSaleService = $this->objectManager->get(PostSale::class);
        $postSaleAction = $this->objectManager->get(DeferredPaymentsPostSaleActionInterface::class);
        $postSaleAction->setPaymentId('1');

        $this->runAssertations($postSaleService->capture($postSaleAction));
        $this->runAssertations($postSaleService->captureRemaining($postSaleAction));
        $this->runAssertations($postSaleService->refund($postSaleAction));
        $this->runAssertations($postSaleService->void($postSaleAction));
        $this->runAssertations($postSaleService->voidRemaining($postSaleAction));
    }

    /**
     * @param $result
     *
     * @return void
     */
    private function runAssertations($result)
    {
        $postSaleEvent = $result->getDataModel();

        $this->assertInstanceOf(\Hokodo\BNPL\Api\Data\PostSaleEventInterface::class, $postSaleEvent);
        $this->assertEquals($this->httpResponse['amount'], $postSaleEvent->getAmount());
    }
}
