<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Hokodo\BNPL\Plugin\Gateway;

use Hokodo\BNPL\Api\Data\DeferredPaymentInterface;
use Hokodo\BNPL\Api\OrderAdapterReaderInterface;
use Hokodo\BNPL\Gateway\Config\Config;
use Hokodo\BNPL\Service\InvoiceCreatorService;

class CreateInvoiceOnPaymentFetchStatus
{
    private Config $config;
    private InvoiceCreatorService $invoiceCreatorService;

    public function __construct(
        Config $config,
        InvoiceCreatorService $invoiceCreatorService
    ) {
        $this->config = $config;
        $this->invoiceCreatorService = $invoiceCreatorService;
    }

    /**
     * @param OrderAdapterReaderInterface $subject
     * @param null                        $result
     * @param array                       $handlingSubject
     * @param array                       $response
     *
     * @return void
     */
    public function afterHandle(OrderAdapterReaderInterface $subject, $result, array $handlingSubject, array $response): void
    {
        if ($response[DeferredPaymentInterface::STATUS] === DeferredPaymentInterface::STATUS_ACCEPTED
            && $this->config->getCreateInvoiceAutomaticallyConfig()
            && ($orderId = $subject->getOrderAdapter($handlingSubject)->getId())) {
            $this->invoiceCreatorService->execute($orderId);
        }
    }
}
