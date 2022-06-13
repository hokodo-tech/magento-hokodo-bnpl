<?php

declare(strict_types=1);
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Plugin\Model\Sales\ResourceModel\Order;

use Hokodo\BNPL\Api\OrderDocumentsManagementInterface;
use Hokodo\BNPL\Model\SaveLog as PaymentLogger;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Model\AbstractModel;
use Magento\Sales\Model\Order;
use Magento\Sales\Model\ResourceModel\Order\Invoice as InvoiceResource;

class Invoice
{
    /**
     * @var PaymentLogger
     */
    private $paymentLogger;

    /**
     * @var OrderDocumentsManagementInterface
     */
    private $orderDocumentManagement;

    /**
     * Constructor For Plugin ResourceModel Invoice.
     *
     * @param PaymentLogger                     $paymentLogger
     * @param OrderDocumentsManagementInterface $orderDocumentManagement
     */
    public function __construct(
        PaymentLogger $paymentLogger,
        OrderDocumentsManagementInterface $orderDocumentManagement,
    ) {
        $this->paymentLogger = $paymentLogger;
        $this->orderDocumentManagement = $orderDocumentManagement;
    }

    /**
     * A function that save result.
     *
     * @param InvoiceResource $subject
     * @param InvoiceResource $result
     * @param AbstractModel   $invoice
     *
     * @return InvoiceResource
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     *
     * @throws LocalizedException
     */
    public function afterSave(
        InvoiceResource $subject,
        InvoiceResource $result,
        AbstractModel $invoice
    ) {
        if (!empty($invoice->getId())) {
            /* @var Order\Invoice $invoice */
            $order = $invoice->getOrder();
            if ($order->getPayment()->getMethod() === 'hokodo_bnpl') {
                if ($order->getState() === Order::STATE_PAYMENT_REVIEW && $order->getOrderApiId()) {
                    $order->getPayment()->update(true);
                    $order->setState(Order::STATE_PROCESSING);
                    $order->setStatus(Order::STATE_PROCESSING);
                    $order->save();
                    $incrementId = $invoice->getIncrementId();
                    $this->orderDocumentManagement->setDocuments($order, 'invoice');
                    $log['updated_order_status'] = 'Created invoice IncrementId: ' .
                        $incrementId . '. Updated order status to: ' . Order::STATE_PROCESSING;
                    $data = [
                        'payment_log_content' => $log,
                        'action_title' => 'After save invoice plugin',
                        'status' => 1,
                    ];
                    $this->paymentLogger->execute($data);
                }
            }
        }

        return $result;
    }
}
