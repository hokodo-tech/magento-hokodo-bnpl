<?php

/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Hokodo\BNPL\Model;

use Hokodo\BNPL\Api\Data\DeferredPaymentInterface;
use Hokodo\BNPL\Api\Data\DeferredPaymentPayloadInterface;
use Hokodo\BNPL\Api\Data\OrderInterface;
use Hokodo\BNPL\Api\DeferredPaymentProcessorInterface;
use Hokodo\BNPL\Gateway\Config\Config;
use Hokodo\BNPL\Gateway\Service\DifferedPayment\OrderProcessor;
use Hokodo\BNPL\Gateway\Service\DifferedPayment\PaymentProcessor;
use Hokodo\BNPL\Model\ResourceModel\HokodoQuote;
use Hokodo\BNPL\Model\ResourceModel\HokodoQuoteDev;
use Hokodo\BNPL\Observer\DataAssignObserver;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Sales\Model\OrderFactory;
use Magento\Sales\Model\ResourceModel\Order;
use Psr\Log\LoggerInterface;

class DeferredPaymentProcessor implements DeferredPaymentProcessorInterface
{
    /**
     * @var OrderFactory
     */
    private OrderFactory $orderFactory;

    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * @var OrderProcessor
     */
    private OrderProcessor $orderProcessor;

    /**
     * @var PaymentProcessor
     */
    private PaymentProcessor $paymentProcessor;

    /**
     * @var Order
     */
    private Order $orderResourceModel;

    /**
     * @var HokodoQuoteDev
     */
    private HokodoQuoteDev $hokodoQuoteDevResourceModel;

    /**
     * @var HokodoQuote
     */
    private HokodoQuote $hokodoQuoteResourceModel;

    /**
     * @var Config
     */
    private Config $config;

    /**
     * @param OrderFactory     $orderFactory
     * @param OrderProcessor   $orderProcessor
     * @param PaymentProcessor $paymentProcessor
     * @param LoggerInterface  $logger
     * @param Order            $orderResourceModel
     * @param HokodoQuoteDev   $hokodoQuoteDevResourceModel
     * @param HokodoQuote      $hokodoQuoteResourceModel
     * @param Config           $config
     */
    public function __construct(
        OrderFactory $orderFactory,
        OrderProcessor $orderProcessor,
        PaymentProcessor $paymentProcessor,
        LoggerInterface $logger,
        Order $orderResourceModel,
        HokodoQuoteDev $hokodoQuoteDevResourceModel,
        HokodoQuote $hokodoQuoteResourceModel,
        Config $config
    ) {
        $this->orderFactory = $orderFactory;
        $this->orderProcessor = $orderProcessor;
        $this->paymentProcessor = $paymentProcessor;
        $this->logger = $logger;
        $this->orderResourceModel = $orderResourceModel;
        $this->hokodoQuoteDevResourceModel = $hokodoQuoteDevResourceModel;
        $this->hokodoQuoteResourceModel = $hokodoQuoteResourceModel;
        $this->config = $config;
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\DeferredPaymentProcessorInterface::process()
     */
    public function process($created, DeferredPaymentPayloadInterface $data)
    {
        $result = false;
        try {
            if ($data->getOrder() && isset($data->getOrder()[OrderInterface::DEFERRED_PAYMENT])) {
                $result = $this->processOrder($data->getOrder());
            }
        } catch (\Exception $e) {
            $data = [
                'message' => 'Hokodo_BNPL: Webhook error with order - ' . $data->getOrder()[OrderInterface::ID],
                'error' => $e->getMessage(),
            ];
            $this->logger->error(__METHOD__, $data);
        }
        return $result;
    }

    /**
     * A function that make process  order.
     *
     * @param array $hokodoOrder
     *
     * @return bool
     *
     * @throws LocalizedException
     */
    private function processOrder(array $hokodoOrder): bool
    {
        $order = $this->getOrder($hokodoOrder);
        if ($order->getId()
            && $order->getPayment()->getAdditionalInformation()[DataAssignObserver::HOKODO_ORDER_ID]
            === $hokodoOrder[OrderInterface::ID]) {
            $deferredPayment = $hokodoOrder[OrderInterface::DEFERRED_PAYMENT];
            $payment = $order->getPayment();
            $status = $deferredPayment[DeferredPaymentInterface::STATUS];
            $transactionId = $deferredPayment[DeferredPaymentInterface::NUMBER];
            $this->orderProcessor->process($order, $status);
            $this->paymentProcessor->process($payment, $status, $transactionId, true);
            return true;
        }
        return false;
    }

    /**
     * Get Sales Order.
     *
     * @param array $hokodoOrder
     *
     * @return \Magento\Sales\Api\Data\OrderInterface
     *
     * @throws LocalizedException
     */
    private function getOrder(array $hokodoOrder): \Magento\Sales\Api\Data\OrderInterface
    {
        $orderIncrementId = $hokodoOrder[OrderInterface::UNIQUE_ID];
        if (str_contains($orderIncrementId, 'magento-temp-')) {
            $orderIncrementId = $this->getReservedOrderIdForQuote($hokodoOrder[OrderInterface::ID]);
        }
        return $this->orderFactory->create()->loadByIncrementId($orderIncrementId);
    }

    /**
     * Get order id from quote.
     *
     * @param string $hokodoOrderId
     *
     * @return string
     *
     * @throws LocalizedException
     */
    private function getReservedOrderIdForQuote(string $hokodoOrderId): string
    {
        $env = $this->config->getEnvironment();
        $connection = $this->orderResourceModel->getConnection();
        $select = $connection->select();
        $select
            ->from(['hq' => $this->getHokodoQuoteResource()->getMainTable()], ['quote_id'])
            ->joinInner(
                ['o' => $this->orderResourceModel->getMainTable()],
                'o.quote_id = hq.quote_id',
                ['increment_id']
            )
            ->where(sprintf('hq.order_id = %s', $connection->quote($hokodoOrderId)));

        $result = $connection->fetchRow($select);
        return $result['increment_id'];
    }

    /**
     * Retrieves the appropriate Hokodo quote resource based on the current environment configuration.
     *
     * @return AbstractResource
     */
    private function getHokodoQuoteResource(): AbstractResource
    {
        if ($this->config->getEnvironment() === Config::ENV_DEV) {
            return $this->hokodoQuoteDevResourceModel;
        }
        return $this->hokodoQuoteResourceModel;
    }
}
