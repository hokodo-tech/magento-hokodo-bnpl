<?php
/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Observer;

use Hokodo\BNPL\Gateway\Service\Order;
use Hokodo\BNPL\Model\RequestBuilder\OrderBuilder;
use Magento\Framework\Event\ObserverInterface;
use Magento\Sales\Api\Data\OrderInterface;
use Psr\Log\LoggerInterface as Logger;

/**
 * Class Hokodo\BNPL\Observer\OrderPlaceSuccessObserver.
 */
class OrderPlaceSuccessObserver implements ObserverInterface
{
    /**
     * @var Logger
     */
    private Logger $logger;

    /**
     * @var OrderBuilder
     */
    private OrderBuilder $orderBuilder;

    /**
     * @var Order
     */
    private Order $orderService;

    /**
     * @param OrderBuilder $orderBuilder
     * @param Order        $orderService
     * @param Logger       $logger
     */
    public function __construct(
        OrderBuilder $orderBuilder,
        Order $orderService,
        Logger $logger
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
        $payment = $order->getPayment();
        if ($payment && $payment->getMethod() === \Hokodo\BNPL\Gateway\Config\Config::CODE) {
            $orderRequest = $this->orderBuilder->buildPatchOrderRequestBase(
                $payment->getAdditionalInformation()[\Hokodo\BNPL\Observer\DataAssignObserver::HOKODO_ORDER_ID]
            );
            $orderRequest->setUniqueId($order->getIncrementId());
            try {
                $this->orderService->patchOrder($orderRequest);
            } catch (\Exception $e) {
                $data = [
                    'message' => 'Hokodo_BNPL: There was an error when patching an order: %1',
                    'error' => $e->getMessage(),
                ];
                $this->logger->error(__METHOD__, $data);
            }
        }
    }
}
