<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Observer;

use Hokodo\BNPL\Model\RequestBuilder\OrderBuilder;
use Hokodo\BNPL\Gateway\Service\Order;
use Hokodo\BNPL\Model\SaveLog as Logger;
use Magento\Framework\Event\ObserverInterface;
use Magento\Sales\Api\Data\OrderInterface;
use Psr\Log\LoggerInterface;

/**
 * Class Hokodo\BNPL\Observer\OrderPlaceSuccessObserver.
 */
class OrderPlaceSuccessObserver implements ObserverInterface
{
    /**
     * @var Logger
     */
    private $logger;

    /**
     * @var OrderBuilder
     */
    private OrderBuilder $orderBuilder;

    /**
     * @var Order
     */
    private Order $orderService;

    /**
     * @param OrderBuilder    $orderBuilder
     * @param Order           $orderService
     * @param LoggerInterface $logger
     */
    public function __construct(
        OrderBuilder $orderBuilder,
        Order $orderService,
        LoggerInterface $logger
    ) {

        $this->orderBuilder = $orderBuilder;
        $this->orderService = $orderService;
        $this->logger = $logger;
    }

    /**
     * @inheritDoc
     *
     * @see \Magento\Framework\Event\ObserverInterface::execute()
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        /** @var OrderInterface $order */
        $order = $observer->getEvent()->getOrder();
        $orderRequest = $this->orderBuilder->buildPatchOrderRequestBase($order->getData('order_api_id'));
        $orderRequest->setUniqueId($order->getIncrementId());
        try {
            $this->orderService->patchOrder($orderRequest);
        } catch (\Exception $e) {
            $this->logger->critical(__('Hokodo_BNPL: There was an error when patching an order: %1', $e->getMessage()));
        }
    }
}
