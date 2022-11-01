<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Gateway\Command;

use Hokodo\BNPL\Api\Data\OrderInformationInterface;
use Hokodo\BNPL\Api\Data\OrderInformationInterfaceFactory;
use Hokodo\BNPL\Api\Data\OrderItemInterface;
use Hokodo\BNPL\Api\Data\OrderItemInterfaceFactory;
use Hokodo\BNPL\Model\SaveLog as Logger;
use Hokodo\BNPL\Service\OrderPostSaleService;
use Hokodo\BNPL\Service\OrderService;
use Magento\Framework\Exception\LocalizedException;
use Magento\Payment\Gateway\CommandInterface;
use Magento\Payment\Gateway\Helper\ContextHelper;
use Magento\Sales\Api\Data\OrderPaymentInterface;
use Magento\Sales\Model\Order\Creditmemo;
use Magento\Sales\Model\Order\Creditmemo\Item;

/**
 * Class Hokodo\BNPL\Gateway\Command\DeferredPaymentCancelCommand.
 */
class DeferredPaymentCancelCommand implements CommandInterface
{
    /**
     * @var OrderPostSaleService
     */
    private $orderPostSaleService;

    /**
     * @var OrderService
     */
    private $orderService;

    /**
     * @var OrderInformationInterfaceFactory
     */
    private $orderInformationFactory;

    /**
     * @var OrderItemInterfaceFactory
     */
    private $orderItemFactory;

    /**
     * @var Logger
     */
    private $logger;

    /**
     * @param OrderPostSaleService             $orderPostSaleService
     * @param OrderService                     $orderService
     * @param OrderInformationInterfaceFactory $orderInformationFactory
     * @param OrderItemInterfaceFactory        $orderItemFactory
     * @param Logger                           $logger
     */
    public function __construct(
        OrderPostSaleService $orderPostSaleService,
        OrderService $orderService,
        OrderInformationInterfaceFactory $orderInformationFactory,
        OrderItemInterfaceFactory $orderItemFactory,
        Logger $logger
    ) {
        $this->orderPostSaleService = $orderPostSaleService;
        $this->orderService = $orderService;
        $this->orderInformationFactory = $orderInformationFactory;
        $this->orderItemFactory = $orderItemFactory;
        $this->logger = $logger;
    }

    /**
     * @inheritDoc
     *
     * @throws LocalizedException
     *
     * @see \Magento\Payment\Gateway\CommandInterface::execute()
     */
    public function execute(array $commandSubject)
    {
        try {
            if (isset($commandSubject['payment'])) {
                $paymentDO = $commandSubject['payment'];
                /* @var OrderPaymentInterface $paymentInfo */
                $paymentInfo = $paymentDO->getPayment();

                ContextHelper::assertOrderPayment($paymentInfo);
                $data = [
                    'payment_log_content' => __('Cancel order id: %1', $paymentInfo->getOrder()->getIncrementId()),
                    'action_title' => 'DeferredPaymentCancelCommand',
                    'status' => 1,
                    'quote_id' => $paymentInfo->getOrder()->getQuoteId(),
                ];
                $this->logger->execute($data);
                if ($paymentInfo->getOrder()->getOrderApiId()) {
                    $this->executeCancelCommand($paymentInfo);
                }
            }
        } catch (\Exception $e) {
            $data = [
                'payment_log_content' => $e->getMessage(),
                'action_title' => 'DeferredPaymentCancelCommand',
                'status' => 0,
            ];
            /*
             * @var OrderPaymentInterface $paymentInfo
             */
            $paymentInfo = $paymentDO->getPayment();
            ContextHelper::assertOrderPayment($paymentInfo);
            if ($paymentInfo->getOrder()->getQuoteId()) {
                $data['quote_id'] = $paymentInfo->getOrder()->getQuoteId();
            }
            $this->logger->execute($data);
            throw $e;
        }
    }

    /**
     * A function that executes cancel command.
     *
     * @param OrderPaymentInterface $paymentInfo
     *
     * @return DeferredPaymentCancelCommand
     */
    private function executeCancelCommand($paymentInfo)
    {
        $apiOrder = $this->getApiOrder($paymentInfo->getOrder()->getOrderApiId());
        if (!$apiOrder->getId()) {
            return $this;
        }
        /* @var Creditmemo $creditmemo */
        $creditmemo = $paymentInfo->getCreditmemo();
        $orderItems = [];
        $refundShipping = (int) ($creditmemo->getShippingAmount() * 100);
        foreach ($apiOrder->getProductItems() as $apiOrderItem) {
            if ($apiOrderItem->getCancelledQuantity() < $apiOrderItem->getQuantity()) {
                $cancelledItem = $this->getCancelledItem($creditmemo->getAllItems(), $apiOrderItem->getItemId());
                if ($cancelledItem && $cancelledItem->getPrice()) {
                    $requestItem = $this->createRequestItem($cancelledItem);
                    $orderItems[] = $requestItem;
                }
            }
        }
        if ($refundShipping > 0) {
            foreach ($apiOrder->getShippingItems() as $apiItem) {
                if ($apiItem->getCancelledQuantity() < $apiItem->getQuantity()) {
                    $orderItem = $this->orderItemFactory->create();
                    $orderItem->setItemId($apiItem->getItemId());
                    if ($refundShipping == $apiItem->getTotalAmount()) {
                        $orderItem->setQuantity($apiItem->getQuantity());
                        $orderItem->setTotalAmount($apiItem->getTotalAmount());
                        $orderItem->setTaxAmount(0);
                    } else {
                        $deduceAmountPercent = ($refundShipping * 100) / $apiItem->getTotalAmount();
                        $cancelQuantity = round(($apiItem->getQuantity() * $deduceAmountPercent) / 100, 3);
                        $orderItem->setQuantity($cancelQuantity);
                        $orderItem->setTotalAmount($refundShipping);
                        $orderItem->setTaxAmount(0);
                    }
                    $orderItems[] = $orderItem;
                }
            }
        }
        if (count($orderItems)) {
            /* @var OrderInformationInterface $returnOrderInformation */
            $cancelledOrderInformation = $this->orderInformationFactory->create();

            $cancelledOrderInformation->setId($paymentInfo->getOrder()->getOrderApiId());
            $cancelledOrderInformation->setItems($orderItems);
            if ($cancelledOrderInformation->getItems()) {
                $this->orderPostSaleService->cancel($cancelledOrderInformation);
            }
        }
        return $this;
    }

    /**
     * A function that creates request item.
     *
     * @param Item $cancelItem
     *
     * @return OrderItemInterface
     */
    private function createRequestItem(Item $cancelItem)
    {
        /**
         * @var OrderItemInterface $orderItem
         */
        $orderItem = $this->orderItemFactory->create();

        /**
         * @var \Magento\Sales\Model\Order\Item $salesOrderItem
         */
        $salesOrderItem = $cancelItem->getOrderItem();

        $orderItem->setItemId($salesOrderItem->getQuoteItemId());
        $orderItem->setQuantity($cancelItem->getQty());

        $taxAmount = $cancelItem->getTaxAmount();
        $totalAmount = ($cancelItem->getRowTotal() + $taxAmount) - $cancelItem->getDiscountAmount();

        $orderItem->setTotalAmount((int) ($totalAmount * 100));
        $orderItem->setTaxAmount(0);

        return $orderItem;
    }

    /**
     * A function that get cancelled item.
     *
     * @param Item[] $items
     * @param string $itemId
     *
     * @return Item
     */
    private function getCancelledItem(array $items, $itemId)
    {
        foreach ($items as $item) {
            /* @var \Magento\Sales\Model\Order\Item $orderItem */
            $orderItem = $item->getOrderItem();
            if ($orderItem->getQuoteItemId() == $itemId) {
                return $item;
            }
        }
        return null;
    }

    /**
     * A function that get api order.
     *
     * @param string $orderApiId
     *
     * @return OrderInformationInterface
     */
    private function getApiOrder($orderApiId)
    {
        $order = $this->orderInformationFactory->create();
        $order->setId($orderApiId);
        return $this->orderService->get($order);
    }
}
