<?php

declare(strict_types=1);
/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Plugin\Sales\Model\ResourceModel\Order;

use Hokodo\BNPL\Api\Data\OrderDocumentInterface;
use Hokodo\BNPL\Api\Data\OrderDocumentInterfaceFactory;
use Hokodo\BNPL\Gateway\Config\Config;
use Hokodo\BNPL\Model\Queue\Handler\Documents;
use Magento\Framework\MessageQueue\PublisherInterface;
use Magento\Framework\Model\AbstractModel;
use Magento\Sales\Model\Order;
use Magento\Sales\Model\ResourceModel\Order\Creditmemo as CreditMemoResource;
use Psr\Log\LoggerInterface;

class CreditMemo
{
    /**
     * @var PublisherInterface
     */
    private PublisherInterface $publisher;

    /**
     * @var OrderDocumentInterfaceFactory
     */
    private OrderDocumentInterfaceFactory $orderDocumentInterfaceFactory;

    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * Constructor For Plugin ResourceModel CreditMemo.
     *
     * @param PublisherInterface            $publisher
     * @param OrderDocumentInterfaceFactory $orderDocumentInterfaceFactory
     * @param LoggerInterface               $logger
     */
    public function __construct(
        PublisherInterface $publisher,
        OrderDocumentInterfaceFactory $orderDocumentInterfaceFactory,
        LoggerInterface $logger
    ) {
        $this->publisher = $publisher;
        $this->orderDocumentInterfaceFactory = $orderDocumentInterfaceFactory;
        $this->logger = $logger;
    }

    /**
     * A function that save result.
     *
     * @param CreditMemoResource $subject
     * @param CreditMemoResource $result
     * @param AbstractModel      $creditMemo
     *
     * @return CreditMemoResource
     */
    public function afterSave(
        CreditMemoResource $subject,
        CreditMemoResource $result,
        AbstractModel $creditMemo
    ) {
        if (!empty($creditMemo->getId()) && $creditMemo->getDoTransaction()) {
            /* @var Order\CreditMemo $creditMemo */
            $order = $creditMemo->getOrder();
            if ($order->getPayment()->getMethod() === Config::CODE) {
                try {
                    /** @var OrderDocumentInterface $orderDocument */
                    $orderDocument = $this->orderDocumentInterfaceFactory->create();
                    $orderDocument
                        ->setOrderId((int) $order->getEntityId())
                        ->setDocumentType(OrderDocumentInterface::TYPE_CREDIT_MEMO)
                        ->setDocumentId($creditMemo->getEntityId());
                    $this->publisher->publish(Documents::TOPIC_NAME, $orderDocument);
                } catch (\Exception $e) {
                    $data = [
                        'message' => 'Hokodo_BNPL: Error publishing creditMemo to queue.',
                        'error' => $e->getMessage(),
                    ];
                    $this->logger->error(__METHOD__, $data);
                }
            }
        }

        return $result;
    }
}
