<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Hokodo\BNPL\Plugin;

use Hokodo\BNPL\Gateway\Config\Config;
use Hokodo\BNPL\Service\InvoiceCreatorService;
use Magento\Sales\Model\Order;
use Magento\Sales\Model\ResourceModel\Order as OrderResourceModel;

class OrderSaveCreateInvoicePlugin
{
    /**
     * @var InvoiceCreatorService
     */
    private $invoiceCreatorService;
    /**
     * @var Config
     */
    private $config;

    /**
     * Construct.
     *
     * @param InvoiceCreatorService $invoiceCreatorService
     * @param Config                $config
     */
    public function __construct(
        InvoiceCreatorService $invoiceCreatorService,
        Config $config
    ) {
        $this->invoiceCreatorService = $invoiceCreatorService;
        $this->config = $config;
    }

    /**
     * Create invoice automatically after the order is accepted in Hokodo.
     *
     * @param OrderResourceModel $subject
     * @param string             $result
     * @param Order              $order
     */
    public function afterSave(OrderResourceModel $subject, $result, Order $order)
    {
        //TODO POST_SALE_V2 review invoice auto creation. If created manually and config enabled the invoice may double
        if ($order->getState() != Order::STATE_PROCESSING ||
            $order->getPayment()->getMethodInstance()->getCode() !== 'hokodo_bnpl'
        ) {
            return;
        }

        if (!$this->config->getCreateInvoiceAutomaticallyConfig((int) $order->getStore()->getId())) {
            return;
        }
        $this->invoiceCreatorService->execute($order->getId());
    }
}
