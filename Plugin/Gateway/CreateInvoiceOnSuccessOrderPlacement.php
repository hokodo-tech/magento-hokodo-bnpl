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
use Hokodo\BNPL\Observer\OrderPlaceSuccessObserver;
use Hokodo\BNPL\Observer\ProcessOrderPlaceObserver;
use Hokodo\BNPL\Service\InvoiceCreatorService;
use Magento\Framework\Event\Observer;

class CreateInvoiceOnSuccessOrderPlacement
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
     * @param OrderPlaceSuccessObserver $subject
     * @param null                      $result
     * @param Observer                  $observer
     *
     * @return void
     */
    public function afterExecute(OrderPlaceSuccessObserver $subject, $result, Observer $observer): void
    {
        $order = $observer->getEvent()->getOrder();
        if ($this->config->getCreateInvoiceAutomaticallyConfig()
            && $order->getPayment()->getData('is_transaction_approved')) {
            $this->invoiceCreatorService->execute($order->getEntityId());
        }

    }
}
