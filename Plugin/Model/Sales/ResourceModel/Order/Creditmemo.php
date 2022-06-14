<?php

declare(strict_types=1);
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Plugin\Model\Sales\ResourceModel\Order;

use Hokodo\BNPL\Gateway\Command\DeferredPaymentCancelCommand;
use Hokodo\BNPL\Gateway\Command\DeferredPaymentRefundCommand;
use Magento\Framework\Model\AbstractModel;
use Magento\Sales\Model\ResourceModel\Order\Creditmemo as CreditmemoResource;

class Creditmemo
{
    /**
     * @var DeferredPaymentRefundCommand
     */
    private $refundCommand;

    /**
     * @var DeferredPaymentCancelCommand
     */
    private $cancelCommand;

    /**
     * A construct.
     *
     * @param DeferredPaymentRefundCommand $refundCommand
     * @param DeferredPaymentCancelCommand $cancelCommand
     */
    public function __construct(
        DeferredPaymentRefundCommand $refundCommand,
        DeferredPaymentCancelCommand $cancelCommand
    ) {
        $this->refundCommand = $refundCommand;
        $this->cancelCommand = $cancelCommand;
    }

    /**
     * A function that save result.
     *
     * @param CreditmemoResource $subject
     * @param CreditmemoResource $result
     * @param AbstractModel      $creditmemo
     *
     * @return CreditmemoResource
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterSave(
        CreditmemoResource $subject,
        CreditmemoResource $result,
        AbstractModel $creditmemo
    ) {
        if (!empty($creditmemo->getId())) {
            $paymentData = null;
            /* @var \Magento\Sales\Model\Order\Creditmemo $creditmemo */
            $order = $creditmemo->getOrder();
            if ($order->getOrderApiId() && $order->getPayment()->getMethod() === 'hokodo_bnpl') {
                $paymentData['payment'] = $order;
                if ($order->hasShipments()) {
                    $this->refundCommand->execute($paymentData);
                } else {
                    $this->cancelCommand->execute($paymentData);
                }
            }
        }

        return $result;
    }
}
