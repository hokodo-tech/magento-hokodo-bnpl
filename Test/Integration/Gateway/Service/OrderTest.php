<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Hokodo\BNPL\Test\Integration\Gateway\Service;

use Hokodo\BNPL\Api\Data\Gateway\CreateOrderRequestInterface;
use Hokodo\BNPL\Api\Data\Gateway\PatchOrderRequestInterface;
use Hokodo\BNPL\Api\Data\OrderInformationInterface;
use Hokodo\BNPL\Gateway\Service\Order;

class OrderTest extends AbstractService
{
    /**
     * @var Order|null
     */
    private $orderService;

    /**
     * @var array
     */
    protected $httpResponse = [
        'id' => 'order-sbMRVMbjXiuKLc9UQutUzW',
        'url' => 'https://some.url/',
        'unique_id' => 'HOK000053',
        'po_number' => '',
        'customer' => [
            'organisation' => 'org-YXP2rH3qwuwiUHyFAXELYM',
            'user' => 'user-p5nGARSgzwa7H4t2L8aa8P',
            'invoice_address' => [
                'name' => 'adasd dasdasd',
                'company_name' => '',
                'address_line1' => 'El Roqueo 78',
                'address_line2' => '',
                'address_line3' => '',
                'city' => 'Palamós',
                'region' => '',
                'postcode' => '17230',
                'country' => 'ES',
                'phone' => '07911123456',
                'email' => '',
            ],
            'delivery_address' => [
                'name' => 'adasd dasdasd',
                'company_name' => '',
                'address_line1' => 'El Roqueo 78',
                'address_line2' => '',
                'address_line3' => '',
                'city' => 'Palamós',
                'region' => '',
                'postcode' => '17230',
                'country' => 'ES',
                'phone' => '07911123456',
                'email' => '',
            ],
            'billing_address' => null,
        ],
        'created' => '2023-06-27T11:23:11.905365Z',
        'currency' => 'EUR',
        'order_date' => '2023-06-27',
        'invoice_date' => null,
        'due_date' => null,
        'paid_date' => null,
        'total_amount' => 24200,
        'tax_amount' => 0,
        'metadata' => [
            'Magento Version: 2.4.4-p2',
            'Hokodo Module Version: 2.1.16',
            'PHP version: 8.1.17',
            'Customer' => [
                'currency' => 'EUR',
                'group_id' => 'General',
                'orders_qty' => 24,
                'total_amount' => 2606.1948,
            ],
        ],
        'items' => [
            [
                'item_id' => 'totals',
                'type' => 'product',
                'description' => 'Combined totals item',
                'metadata' => null,
                'reference' => '',
                'category' => '',
                'supplier_id' => '',
                'supplier_name' => '',
                'quantity' => '1.000',
                'unit_price' => 24200,
                'total_amount' => 24200,
                'tax_amount' => 0,
                'tax_rate' => '0.00',
            ],
            [
                'item_id' => 'shipping',
                'type' => 'shipping',
                'description' => 'Shipping',
                'metadata' => null,
                'reference' => '',
                'category' => '',
                'supplier_id' => '',
                'supplier_name' => '',
                'quantity' => '1.000',
                'unit_price' => 1000,
                'total_amount' => 1000,
                'tax_amount' => 0,
                'tax_rate' => '0.00',
            ],
        ],
        'payment_offer' => 'offr-9F9iibZ2EpMhrWgaLd7Ah5',
        'status' => 'draft',
        'pay_method' => 'unknown',
        'deferred_payment' => 'defpay-BfWKaCKPbSBXHghFid4Ng9',
    ];

    protected function setUp(): void
    {
        parent::setUp();
        $this->orderService = $this->objectManager->get(Order::class);
    }

    /**
     * @return void
     *
     * @throws \Magento\Framework\Exception\NotFoundException
     * @throws \Magento\Payment\Gateway\Command\CommandException
     */
    public function testCreate()
    {
        $orderCreateRequest = $this->objectManager->get(CreateOrderRequestInterface::class);
        $result = $this->orderService->createOrder($orderCreateRequest);

        $order = $result->getDataModel();
        $this->assertInstanceOf(OrderInformationInterface::class, $order);
        $this->assertCount(count($this->httpResponse['items']), $order->getItems());
        $this->assertEquals($this->httpResponse['deferred_payment'], $order->getDeferredPayment());
        $this->assertEquals(
            $this->httpResponse['customer']['organisation'],
            $order->getCustomer()->getOrganisation()
        );
    }

    /**
     * @return void
     *
     * @throws \Magento\Framework\Exception\NotFoundException
     * @throws \Magento\Payment\Gateway\Command\CommandException
     */
    public function testPatch()
    {
        $orderPatchRequest = $this->objectManager->get(PatchOrderRequestInterface::class);
        $orderPatchRequest->setOrderId('1');
        $result = $this->orderService->patchOrder($orderPatchRequest);

        $order = $result->getDataModel();
        $this->assertInstanceOf(OrderInformationInterface::class, $order);
        $this->assertCount(count($this->httpResponse['items']), $order->getItems());
        $this->assertEquals($this->httpResponse['deferred_payment'], $order->getDeferredPayment());
        $this->assertEquals(
            $this->httpResponse['customer']['organisation'],
            $order->getCustomer()->getOrganisation()
        );
    }

    /**
     * @return void
     *
     * @throws \Magento\Framework\Exception\NotFoundException
     * @throws \Magento\Payment\Gateway\Command\CommandException
     */
    public function testGet()
    {
        $result = $this->orderService->getOrder(['id' => '1']);

        $order = $result->getDataModel();
        $this->assertInstanceOf(OrderInformationInterface::class, $order);
        $this->assertCount(count($this->httpResponse['items']), $order->getItems());
        $this->assertEquals($this->httpResponse['deferred_payment'], $order->getDeferredPayment());
        $this->assertEquals(
            $this->httpResponse['customer']['organisation'],
            $order->getCustomer()->getOrganisation()
        );
    }
}
