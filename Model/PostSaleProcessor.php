<?php

/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Model;

use Hokodo\BNPL\Api\Data\OrderInformationInterface;
use Hokodo\BNPL\Api\Data\OrderInformationInterfaceFactory;
use Hokodo\BNPL\Api\Data\OrderItemInterface;
use Hokodo\BNPL\Api\Data\OrderItemInterfaceFactory;
use Hokodo\BNPL\Model\SaveLog as PaymentLogger;
use Hokodo\BNPL\Service\OrderPostSaleService;
use Hokodo\BNPL\Service\OrderService;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Payment\Model\Method\Logger;
use Magento\Sales\Api\Data\ShipmentInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Tax\Model\Config as TaxConfig;

/**
 * Class Hokodo\BNPL\Model\PostSaleProcessor.
 */
class PostSaleProcessor
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
     * @var ScopeConfigInterface
     */
    private $scopeConfiguration;

    /**
     * @var Logger
     */
    private $logger;

    /**
     * @var PaymentLogger
     */
    private $paymentLogger;

    /**
     * A constructor.
     *
     * @param OrderPostSaleService             $orderPostSaleService
     * @param OrderService                     $orderService
     * @param OrderInformationInterfaceFactory $orderInformationFactory
     * @param OrderItemInterfaceFactory        $orderItemFactory
     * @param ScopeConfigInterface             $scopeConfiguration
     * @param Logger                           $logger
     * @param SaveLog                          $paymentLogger
     */
    public function __construct(
        OrderPostSaleService $orderPostSaleService,
        OrderService $orderService,
        OrderInformationInterfaceFactory $orderInformationFactory,
        OrderItemInterfaceFactory $orderItemFactory,
        ScopeConfigInterface $scopeConfiguration,
        Logger $logger,
        PaymentLogger $paymentLogger
    ) {
        $this->orderPostSaleService = $orderPostSaleService;
        $this->orderService = $orderService;
        $this->orderInformationFactory = $orderInformationFactory;
        $this->orderItemFactory = $orderItemFactory;
        $this->scopeConfiguration = $scopeConfiguration;
        $this->logger = $logger;
        $this->paymentLogger = $paymentLogger;
    }

    /**
     * A function that checks fulfill of logs.
     *
     * @param ShipmentInterface $shipment
     *
     * @return \Hokodo\BNPL\Model\PostSaleProcessor
     */
    public function fulfill(ShipmentInterface $shipment)
    {
        /**
         * @var \Magento\Sales\Model\Order $order
         */
        $order = $shipment->getOrder();
        $log = [];
        $log['start_shipment_process'] = 'Shipment processing, shipment increment id: ' . $shipment->getIncrementId();
        if ($order->getOrderApiId() && $order->getDeferredPaymentId()) {
            $apiOrder = $this->getApiOrder($order->getOrderApiId());
            if ($apiOrder->getId()) {
                $orderItems = [];
                foreach ($apiOrder->getProductItems() as $apiOrderItem) {
                    if ($apiOrderItem->getFulfilledQuantity() != $apiOrderItem->getQuantity()) {
                        $shipItem = $this->getShipItem($shipment->getAllItems(), $apiOrderItem->getItemId());
                        if ($shipItem) {
                            $orderItems[] = $this->createRequestItem($shipItem);
                        }
                    }
                }
                $log['count_order_items'] = count($orderItems);
                if (count($apiOrder->getShippingItems())) {
                    /*
                     * @var \Hokodo\BNPL\Api\Data\OrderItemInterface $apiItem
                     */
                    $log['count_order_items_01'] = 'O1 = ' . count($orderItems);
                    foreach ($apiOrder->getShippingItems() as $apiItem) {
                        $remainingQty = $apiItem->getFulfilledQuantity();
                        $remainingQty += $apiItem->getCancelledQuantity();
                        $remainingQty += $apiItem->getReturnedQuantity();
                        if ($remainingQty < $apiItem->getQuantity()) {
                            /**
                             * @var OrderItemInterface $orderItem
                             */
                            $orderItem = $this->createShippingItem($apiItem);
                            $orderItems[] = $orderItem;
                        }
                    }
                }
                $log['count_order_items_02'] = 'O2 = ' . count($orderItems);
                $data = [
                    'payment_log_content' => $log,
                    'action_title' => 'PostSaleProcessor::fulfill',
                    'status' => 1,
                    'quote_id' => $order->getQuoteId(),
                ];
                $this->paymentLogger->execute($data);
                if (count($orderItems)) {
                    /**
                     * @var OrderInformationInterface $orderInformation
                     */
                    $orderInformation = $this->orderInformationFactory->create();

                    $orderInformation->setId($order->getOrderApiId());
                    $orderInformation->setItems($orderItems);
                    $resultOrderInformation = $this->requestFulfill($orderInformation, $shipment);
                    if ($resultOrderInformation->getId()) {
                        foreach ($shipment->getAllItems() as $shipItem) {
                            /**
                             * @var \Magento\Sales\Model\Order\Item $salesOrderItem
                             */
                            $salesOrderItem = $order->getItemById($shipItem->getOrderItemId());
                            $salesOrderItem->setLockedDoInvoice(null);
                        }
                    }
                }
            }
        }

        return $this;
    }

    /**
     * A function that gets ship item.
     *
     * @param \Magento\Sales\Model\Order\Shipment\Item[] $items
     * @param string                                     $itemId
     *
     * @return \Magento\Sales\Model\Order\Shipment\Item
     */
    private function getShipItem($items, $itemId)
    {
        foreach ($items as $item) {
            /**
             * @var \Magento\Sales\Model\Order\Item $orderItem
             */
            $orderItem = $item->getOrderItem();
            $data = [
                'payment_log_content' => 'getShipItem ' . $itemId,
                'action_title' => 'PostSaleProcessor::getShipItem',
                'status' => 1,
                'quote_id' => $orderItem->getQuoteItemId(),
            ];
            $this->paymentLogger->execute($data);

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

    /**
     * A function that creates request item.
     *
     * @param \Magento\Sales\Model\Order\Shipment\Item $shipItem
     *
     * @return \Hokodo\BNPL\Api\Data\OrderItemInterface
     */
    private function createRequestItem(\Magento\Sales\Model\Order\Shipment\Item $shipItem)
    {
        /**
         * @var OrderItemInterface $orderItem
         */
        $orderItem = $this->orderItemFactory->create();

        /**
         * @var \Magento\Sales\Model\Order\Item $salesOrderItem
         */
        $salesOrderItem = $shipItem->getOrderItem();

        $orderItem->setItemId($salesOrderItem->getQuoteItemId());
        $orderItem->setQuantity($shipItem->getQty());
        $shipmentItemTotalInclTax = ($salesOrderItem->getRowTotalInclTax()
                / $salesOrderItem->getQtyOrdered()) * $shipItem->getQty();
        $shipmentItemTotalExclTax = $salesOrderItem->getBasePrice() * $shipItem->getQty();
        $currentTax = $shipmentItemTotalInclTax / $shipmentItemTotalExclTax;
        $discountAmount = ($salesOrderItem->getDiscountAmount() * $shipItem->getQty())
        / $salesOrderItem->getQtyOrdered();
        //Item total calculation adjustment based on tax settings in Magento
        if ($this->isApplyTaxAdjustmen($salesOrderItem->getStoreId())) {
            $totalAmount = $shipmentItemTotalInclTax - $discountAmount * $currentTax;
        } else {
            $totalAmount = $shipmentItemTotalInclTax - $discountAmount;
        }
        $taxAmount = ($salesOrderItem->getTaxAmount() * $shipItem->getQty())
        / $salesOrderItem->getQtyOrdered();
        $orderItem->setTotalAmount((int) round($totalAmount * 100));
        $orderItem->setTaxAmount((int) round($taxAmount * 100));

        return $orderItem;
    }

    /**
     * Create request for shipping items.
     *
     * @param \Hokodo\BNPL\Api\Data\OrderItemInterface $apiItem
     *
     * @return \Hokodo\BNPL\Api\Data\OrderItemInterface
     */
    private function createShippingItem(OrderItemInterface $apiItem)
    {
        /**
         * @var OrderItemInterface $orderItem
         */
        $orderItem = $this->orderItemFactory->create();

        $shippingQty = $apiItem->getQuantity();
        $totalAmount = $apiItem->getTotalAmount();
        $taxAmount = $apiItem->getTaxAmount();
        $cancelledQty = $apiItem->getCancelledQuantity();
        $totalAmount = $apiItem->getTotalAmount();
        if ($cancelledQty > 0) {
            $cancelledInfo = $apiItem->getCancelledInfo();
            foreach ($cancelledInfo as $cancelled) {
                $shippingQty -= $cancelled->getQuantity();
                $totalAmount -= $cancelled->getTotalAmount();
                $taxAmount -= $cancelled->getTaxAmount();
            }
        }
        $orderItem->setItemId($apiItem->getItemId());
        $orderItem->setQuantity($shippingQty);
        $orderItem->setTotalAmount($totalAmount);
        $orderItem->setTaxAmount($taxAmount);

        return $orderItem;
    }

    /**
     * A function that gets request fulfill.
     *
     * @param OrderInformationInterface $order
     * @param ShipmentInterface         $shipment
     *
     * @throws Exception
     *
     * @return \Hokodo\BNPL\Api\Data\OrderInformationInterface|null
     */
    private function requestFulfill(OrderInformationInterface $order, ShipmentInterface $shipment)
    {
        try {
            return $this->orderPostSaleService->fulfill($order, $shipment);
        } catch (\Exception $e) {
            $data = [
                'payment_log_content' => $e->getMessage(),
                'action_title' => 'PostSaleProcessor::requestFulfill',
                'status' => 0,
            ];
            $this->paymentLogger->execute($data);
            throw new LocalizedException(__('Unable to create shipping right now. Please try again later.'));
        }
    }

    /**
     * Whether tax adjustment is necessary.
     *
     * @param int $storeId
     *
     * @return bool
     */
    private function isApplyTaxAdjustmen($storeId = 0)
    {
        return $this->scopeConfiguration->getValue(
            TaxConfig::CONFIG_XML_PATH_APPLY_AFTER_DISCOUNT,
            ScopeInterface::SCOPE_STORE,
            $storeId
        ) &&
            !$this->scopeConfiguration->getValue(
                TaxConfig::CONFIG_XML_PATH_PRICE_INCLUDES_TAX,
                ScopeInterface::SCOPE_STORE,
                $storeId
            );
    }
}
