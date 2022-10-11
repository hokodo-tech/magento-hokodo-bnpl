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
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Payment\Gateway\CommandInterface;
use Magento\Payment\Gateway\Helper\ContextHelper;
use Magento\Sales\Api\Data\OrderPaymentInterface;
use Magento\Sales\Model\Order\Creditmemo;
use Magento\Sales\Model\Order\Creditmemo\Item;
use Magento\Store\Model\ScopeInterface;
use Magento\Tax\Model\Config as TaxConfig;

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
     * @var ScopeConfigInterface
     */
    private $scopeConfiguration;

    /**
     * @param OrderPostSaleService             $orderPostSaleService
     * @param OrderService                     $orderService
     * @param OrderInformationInterfaceFactory $orderInformationFactory
     * @param OrderItemInterfaceFactory        $orderItemFactory
     * @param ScopeConfigInterface             $scopeConfiguration
     * @param Logger                           $logger
     */
    public function __construct(
        OrderPostSaleService $orderPostSaleService,
        OrderService $orderService,
        OrderInformationInterfaceFactory $orderInformationFactory,
        OrderItemInterfaceFactory $orderItemFactory,
        ScopeConfigInterface $scopeConfiguration,
        Logger $logger
    ) {
        $this->orderPostSaleService = $orderPostSaleService;
        $this->orderService = $orderService;
        $this->orderInformationFactory = $orderInformationFactory;
        $this->orderItemFactory = $orderItemFactory;
        $this->logger = $logger;
        $this->scopeConfiguration = $scopeConfiguration;
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
                    'payment_log_content' => __('Refund order id: %1', $paymentInfo->getOrder()->getIncrementId()),
                    'action_title' => 'DeferredPaymentRefundCommand',
                    'status' => 1,
                    'quote_id' => $paymentInfo->getOrder()->getQuoteId(),
                ];
                $this->logger->execute($data);
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
            /* @var OrderPaymentInterface $paymentInfo */
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
            /* @var Creditmemo $creditmemo */
            $creditmemo = $paymentInfo->getCreditmemo();
            $orderItems = [];
            $refundShipping = (int) ($creditmemo->getShippingAmount() * 100);
            foreach ($apiOrder->getProductItems() as $apiOrderItem) {
                if ($apiOrderItem->getFulfilledQuantity() > 0
                    && $apiOrderItem->getReturnedQuantity() == 0
                ) {
                    $returnItem = $this->getReturnedItem($creditmemo->getAllItems(), $apiOrderItem->getItemId());
                    if ($returnItem && $returnItem->getPrice()) {
                        $requestItem = $this->createRequestItem($returnItem);
                        $orderItems[] = $requestItem;
                    }
                }
            }
            foreach ($apiOrder->getShippingItems() as $apiItem) {
                if ($apiItem->getReturnedQuantity() != $apiItem->getQuantity()) {
                    if ($apiItem->getFulfilledQuantity() > 0
                        && $apiItem->getReturnedQuantity() == 0
                    ) {
                        $orderItem = $this->orderItemFactory->create();
                        $orderItem->setItemId($apiItem->getItemId());
                        if ($refundShipping == $apiItem->getTotalAmount()) {
                            $orderItem->setQuantity($apiItem->getQuantity());
                            $orderItem->setTotalAmount($apiItem->getTotalAmount());
                            $orderItem->setTaxAmount($apiItem->getTaxAmount());
                        } else {
                            $deduceAmountPercent = ($refundShipping * 100) / $apiItem->getTotalAmount();
                            $returnQuantity = round(($apiItem->getQuantity() * $deduceAmountPercent) / 100, 3);
                            $orderItem->setQuantity($returnQuantity);
                            $orderItem->setTotalAmount($refundShipping);
                            $orderItem->setTaxAmount(0);
                        }
                        $orderItems[] = $orderItem;
                    }
                }
            }
            if (count($orderItems)) {
                $returnOrderInformation = $this->orderInformationFactory->create();
                $returnOrderInformation->setId($paymentInfo->getOrder()->getOrderApiId());
                $returnOrderInformation->setItems($orderItems);
                if ($returnOrderInformation->getItems()) {
                    $this->orderPostSaleService->return($returnOrderInformation);
                }
            }
        }

        return $this;
    }

    /**
     * A function that get returned item.
     *
     * @param Item[] $items
     * @param string $itemId
     *
     * @return Item
     */
    private function getReturnedItem(array $items, $itemId)
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
     * A function that creates request item.
     *
     * @param Item $returnItem
     *
     * @return OrderItemInterface
     */
    private function createRequestItem(Item $returnItem)
    {
        $orderItem = $this->orderItemFactory->create();
        /* @var \Magento\Sales\Model\Order\Item $salesOrderItem */
        $salesOrderItem = $returnItem->getOrderItem();
        $orderItem->setItemId($salesOrderItem->getQuoteItemId());
        $orderItem->setQuantity($returnItem->getQty());
        $taxAmount = $returnItem->getTaxAmount();
        $currentTax = $returnItem->getRowTotal() > 0 ?
            round($returnItem->getRowTotalInclTax() / $returnItem->getRowTotal(), 2) :
            $returnItem->getRowTotalInclTax();
        //Item total calculation adjustment based on tax settings in Magento
        if ($this->isApplyTaxAdjustmen($returnItem->getStoreId())) {
            $totalAmount = $returnItem->getRowTotalInclTax() - $returnItem->getDiscountAmount() * $currentTax;
        } else {
            $totalAmount = $returnItem->getRowTotalInclTax() - $returnItem->getDiscountAmount();
        }
        $orderItem->setTotalAmount((int) ($totalAmount * 100));
        $orderItem->setTaxAmount((int) ($taxAmount * 100));

        return $orderItem;
    }

    /**
     * A function that gets api order.
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
