<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Gateway\Command;

use Hokodo\BNPL\Api\Data\OrderInformationInterface;
use Hokodo\BNPL\Api\Data\OrderInformationInterfaceFactory;
use Hokodo\BNPL\Model\SaveLog as Logger;
use Hokodo\BNPL\Service\OrderPostSaleService;
use Hokodo\BNPL\Service\OrderService;
use Magento\Framework\Exception\LocalizedException;
use Magento\Payment\Gateway\CommandInterface;
use Magento\Payment\Gateway\Helper\ContextHelper;
use Magento\Sales\Api\Data\OrderPaymentInterface;

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
     * @var Logger
     */
    private $logger;

    /**
     * @param OrderPostSaleService             $orderPostSaleService
     * @param OrderService                     $orderService
     * @param OrderInformationInterfaceFactory $orderInformationFactory
     * @param Logger                           $logger
     */
    public function __construct(
        OrderPostSaleService $orderPostSaleService,
        OrderService $orderService,
        OrderInformationInterfaceFactory $orderInformationFactory,
        Logger $logger
    ) {
        $this->orderPostSaleService = $orderPostSaleService;
        $this->orderService = $orderService;
        $this->orderInformationFactory = $orderInformationFactory;
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
                $paymentInfo = $paymentDO->getPayment();

                ContextHelper::assertOrderPayment($paymentInfo);

                if ($paymentInfo->getOrder()->getOrderApiId()) {
                    $apiOrder = $this->getApiOrder($paymentInfo->getOrder()->getOrderApiId());
                    if ($apiOrder->getId()) {
                        $this->orderPostSaleService->cancel($apiOrder);
                    }
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
