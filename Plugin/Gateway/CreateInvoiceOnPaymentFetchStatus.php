<?php

/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Hokodo\BNPL\Plugin\Gateway;

use Hokodo\BNPL\Api\Data\DeferredPaymentInterface;
use Hokodo\BNPL\Gateway\Config\Config;
use Hokodo\BNPL\Service\InvoiceCreatorService;
use Magento\Payment\Gateway\Response\HandlerInterface;

class CreateInvoiceOnPaymentFetchStatus
{
    /**
     * @var Config
     */
    private Config $config;

    /**
     * @var InvoiceCreatorService
     */
    private InvoiceCreatorService $invoiceCreatorService;

    /**
     * @param Config                $config
     * @param InvoiceCreatorService $invoiceCreatorService
     */
    public function __construct(
        Config $config,
        InvoiceCreatorService $invoiceCreatorService
    ) {
        $this->config = $config;
        $this->invoiceCreatorService = $invoiceCreatorService;
    }

    /**
     * Create invoice if feature is on in config.
     *
     * @param HandlerInterface $subject
     * @param null             $result
     * @param array            $handlingSubject
     * @param array            $response
     *
     * @return void
     */
    public function afterHandle(
        HandlerInterface $subject,
        $result,
        array $handlingSubject,
        array $response
    ): void {
        $order = $handlingSubject['payment']->getOrder();
        if ($response[DeferredPaymentInterface::STATUS] === DeferredPaymentInterface::STATUS_ACCEPTED
            && $this->config->getCreateInvoiceAutomaticallyConfig($order->getStoreId())
            && ($order->getId())) {
            $this->invoiceCreatorService->execute($order->getId());
        }
    }
}
