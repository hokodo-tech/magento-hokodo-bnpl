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

/**
 * Class Hokodo\BNPL\Gateway\Command\DeferredPaymentRefundCommand.
 */
class DeferredPaymentRefundCommand implements CommandInterface
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
                $paymentDO->getApiOrder();
                /**
                 * @var OrderPaymentInterface $paymentInfo
                 */
                $paymentInfo = $paymentDO->getPayment();

                ContextHelper::assertOrderPayment($paymentInfo);

                if ($paymentInfo->getOrder()->getOrderApiId()) {
                    $this->executeRefundCommand($paymentInfo);
                }
            }
        } catch (\Exception $e) {
            $data = [
                'payment_log_content' => $e->getMessage(),
                'action_title' => 'DeferredPaymentRefundCommand',
                'status' => 0,
            ];
            /*
             * @var OrderPaymentInterface $paymentInfo
             */
            $paymentDO->getApiOrder();
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
     * A function that executes refund command.
     *
     * @param OrderPaymentInterface $paymentInfo
     *
     * @return DeferredPaymentRefundCommand
     */
    private function executeRefundCommand($paymentInfo)
    {
        $apiOrder = $this->getApiOrder($paymentInfo->getOrder()->getOrderApiId());
        if ($apiOrder->getId()) {

            /**
             * @var Creditmemo $creditmemo
             */
            $creditmemo = $paymentInfo->getCreditmemo();

            $orderItems = [];
            $grandTotal = 0;
            foreach ($apiOrder->getProductItems() as $apiOrderItem) {
                if ($apiOrderItem->getReturnedQuantity() != $apiOrderItem->getQuantity()) {
                    $returnItem = $this->getReturnedItem($creditmemo->getAllItems(), $apiOrderItem->getItemId());
                    if ($returnItem && $returnItem->getPrice()) {
                        $requestItem = $this->createRequestItem($returnItem);
                        $orderItems[] = $requestItem;
                        $grandTotal += $requestItem->getTotalAmount() / 100 + $requestItem->getTaxAmount() / 100;
                    }
                }
            }

            foreach ($apiOrder->getShippingItems() as $apiItem) {
                if ($apiItem->getReturnedQuantity() != $apiItem->getQuantity()
                    && $creditmemo->getShippingAmount() == ($apiItem->getTotalAmount() / 100)) {
                    /**
                     * @var OrderItemInterface $orderItem
                     */
                    $orderItem = $this->orderItemFactory->create();

                    $orderItem->setItemId($apiItem->getItemId());
                    $orderItem->setQuantity($apiItem->getQuantity());

                    $orderItem->setTotalAmount($apiItem->getTotalAmount());
                    $orderItem->setTaxAmount($apiItem->getTaxAmount());
                    $orderItems[] = $orderItem;
                    $grandTotal += $orderItem->getTotalAmount() / 100 + $orderItem->getTaxAmount() / 100;
                }
            }

            if (count($orderItems)) {

                /**
                 * @var OrderInformationInterface $returnOrderInformation
                 */
                $returnOrderInformation = $this->orderInformationFactory->create();

                $returnOrderInformation->setId($paymentInfo->getOrder()->getOrderApiId());
                $returnOrderInformation->setItems($orderItems);

                $this->orderPostSaleService->return($returnOrderInformation);
            }

            if (($creditmemo->getGrandTotal() - $grandTotal) != 0) {
                /**
                 * @var OrderInformationInterface $discountOrderInformation
                 */
                $discountOrderInformation = $this->orderInformationFactory->create();

                $discountOrderInformation->setId($paymentInfo->getOrder()->getOrderApiId());

                /**
                 * @var OrderItemInterface $orderItem
                 */
                $orderItem = $this->orderItemFactory->create();

                $orderItem->setItemId($paymentInfo->getOrder()->getQuoteId() . '-discount');
                $orderItem->setQuantity(1);
                $unitPrice = (0 - (int) ($creditmemo->getGrandTotal() - $grandTotal) * 100);
                $orderItem->setUnitPrice($unitPrice);

                $orderItem->setTotalAmount($unitPrice);
                $orderItem->setTaxAmount(0);

                $discountOrderItems = [$orderItem];

                $discountOrderInformation->setItems($discountOrderItems);

                $this->orderPostSaleService->discount($discountOrderInformation);
            }
        }

        return $this;
    }

    /**
     * A function that get returned item.
     *
     * @param \Magento\Sales\Model\Order\Creditmemo\Item[] $items
     * @param string                                       $itemId
     *
     * @return \Magento\Sales\Model\Order\Creditmemo\Item
     */
    private function getReturnedItem(array $items, $itemId)
    {
        foreach ($items as $item) {
            /**
             * @var \Magento\Sales\Model\Order\Item $orderItem
             */
            $orderItem = $item->getOrderItem();
            if ($orderItem->getQuoteItemId() == $itemId) {
                return $item;
            }
        }
        return null;
    }

    /**
     * A function that creates request item.
     *
     * @param \Magento\Sales\Model\Order\Creditmemo\Item $returnItem
     *
     * @return \Hokodo\BNPL\Api\Data\OrderItemInterface
     */
    private function createRequestItem(Creditmemo\Item $returnItem)
    {
        /**
         * @var \Hokodo\BNPL\Api\Data\OrderItemInterface $orderItem
         */
        $orderItem = $this->orderItemFactory->create();

        /**
         * @var \Magento\Sales\Model\Order\Item $salesOrderItem
         */
        $salesOrderItem = $returnItem->getOrderItem();

        $orderItem->setItemId($salesOrderItem->getQuoteItemId());
        $orderItem->setQuantity($returnItem->getQty());

        $totalAmount = $returnItem->getRowTotal() - $returnItem->getDiscountAmount();
        $taxAmount = $returnItem->getTaxAmount();

        $orderItem->setTotalAmount((int) ($totalAmount * 100));
        $orderItem->setTaxAmount((int) ($taxAmount * 100));

        return $orderItem;
    }

    /**
     * A function that gets api order.
     *
     * @param string $orderApiId
     *
     * @return \Hokodo\BNPL\Api\Data\OrderInformationInterface
     */
    private function getApiOrder($orderApiId)
    {
        $order = $this->orderInformationFactory->create();
        $order->setId($orderApiId);
        return $this->orderService->get($order);
    }
}
