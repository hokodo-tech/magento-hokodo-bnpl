<?php

declare(strict_types=1);
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Plugin\Model\Sales\ResourceModel\Order;

use Hokodo\BNPL\Api\Data\OrderDocumentInterface;
use Hokodo\BNPL\Api\Data\OrderDocumentInterfaceFactory;
use Hokodo\BNPL\Gateway\Config\Config;
use Hokodo\BNPL\Model\Queue\Handler\Documents;
use Magento\Framework\MessageQueue\PublisherInterface;
use Magento\Framework\Model\AbstractModel;
use Magento\Sales\Model\Order;
use Magento\Sales\Model\ResourceModel\Order\Invoice as InvoiceResource;
use Psr\Log\LoggerInterface;

class Invoice
{
    /**
     * @var PublisherInterface
     */
    private PublisherInterface $publisher;

    /**
     * @var OrderDocumentInterfaceFactory
     */
    private OrderDocumentInterfaceFactory $orderDocumentInterfaceFactory;

    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * Constructor For Plugin ResourceModel Invoice.
     *
     * @param PublisherInterface            $publisher
     * @param OrderDocumentInterfaceFactory $orderDocumentInterfaceFactory
     * @param LoggerInterface               $logger
     */
    public function __construct(
        PublisherInterface $publisher,
        OrderDocumentInterfaceFactory $orderDocumentInterfaceFactory,
        LoggerInterface $logger
    ) {
        $this->publisher = $publisher;
        $this->orderDocumentInterfaceFactory = $orderDocumentInterfaceFactory;
        $this->logger = $logger;
    }

    /**
     * A function that save result.
     *
     * @param InvoiceResource $subject
     * @param InvoiceResource $result
     * @param AbstractModel   $invoice
     *
     * @return InvoiceResource
     */
    public function afterSave(
        InvoiceResource $subject,
        InvoiceResource $result,
        AbstractModel $invoice
    ) {
        if (!empty($invoice->getId())) {
            /* @var Order\Invoice $invoice */
            $order = $invoice->getOrder();
            if ($order->getPayment()->getMethod() === Config::CODE) {
                try {
                    /** @var OrderDocumentInterface $orderDocument */
                    $orderDocument = $this->orderDocumentInterfaceFactory->create();
                    $orderDocument
                        ->setOrderId((int) $order->getEntityId())
                        ->setDocumentType('invoice')
                        ->setDocumentId($invoice->getEntityId());
                    $this->publisher->publish(Documents::TOPIC_NAME, $orderDocument);
                } catch (\Exception $e) {
                    $data = [
                        'message' => 'Hokodo_BNPL: Error publishing invoice to queue.',
                        'error' => $e->getMessage(),
                    ];
                    $this->logger->error(__METHOD__, $data);
                }
            }
        }

        return $result;
    }
}
