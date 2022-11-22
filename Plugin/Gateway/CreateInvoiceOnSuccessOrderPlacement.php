<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Hokodo\BNPL\Plugin\Gateway;

use Hokodo\BNPL\Gateway\Config\Config;
use Hokodo\BNPL\Observer\OrderPlaceSuccessObserver;
use Hokodo\BNPL\Service\InvoiceCreatorService;
use Magento\Framework\Event\Observer;

class CreateInvoiceOnSuccessOrderPlacement
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
