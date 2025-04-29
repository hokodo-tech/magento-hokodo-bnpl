<?php

/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Hokodo\BNPL\Gateway\Service\DifferedPayment;

use Hokodo\BNPL\Api\Data\DeferredPaymentInterface;
use Hokodo\BNPL\Gateway\Config\Config;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Model\Order;

class OrderProcessor
{
    /**
     * @var OrderRepositoryInterface
     */
    private OrderRepositoryInterface $orderRepository;

    /**
     * @var Config
     */
    private Config $config;

    /**
     * @param OrderRepositoryInterface $orderRepository
     * @param Config                   $config
     */
    public function __construct(
        OrderRepositoryInterface $orderRepository,
        Config $config
    ) {
        $this->orderRepository = $orderRepository;
        $this->config = $config;
    }

    /**
     * Process Order.
     *
     * @param OrderInterface $order
     * @param string         $status
     *
     * @return void
     */
    public function process(
        OrderInterface $order,
        string $status
    ): void {
        switch ($status) {
            case DeferredPaymentInterface::STATUS_ACCEPTED:
                if ($order->getState() === Order::STATE_PAYMENT_REVIEW) {
                    $state = $this->config->getValue(Config::ORDER_STATUS_REVIEW_COMPLETED);
                    $order->setState($state);
                    $order->setStatus($order->getConfig()->getStateDefaultStatus($state));
                    $this->orderRepository->save($order);
                }
                break;
            case DeferredPaymentInterface::STATUS_PENDING:
            case DeferredPaymentInterface::STATUS_ACTION_REQUIRED:
            case DeferredPaymentInterface::STATUS_REJECTED:
                break;
            case DeferredPaymentInterface::STATUS_PENDING_PAYMENT:
                if ($order->getState() === Order::STATE_PAYMENT_REVIEW) {
                    $order->setStatus('hokodo_pending_upfront_payment');
                    $this->orderRepository->save($order);
                }
                break;
            case DeferredPaymentInterface::STATUS_CAPTURED:
                if ($order->getState() === Order::STATE_PENDING_PAYMENT ||
                    $order->getState() === 'pending') {
                    $order->setState(Order::STATE_PROCESSING);
                    $order->setStatus($order->getConfig()->getStateDefaultStatus(Order::STATE_PROCESSING));
                    $this->orderRepository->save($order);
                }
                break;
        }
    }
}
