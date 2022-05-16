<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Gateway\Request;

use Hokodo\BNPL\Gateway\OrderSubjectReader;
use Magento\Payment\Gateway\Request\BuilderInterface;

/**
 * Class Hokodo\BNPL\Gateway\Request\OrderItemsBuilder.
 */
class OrderItemsBuilder implements BuilderInterface
{
    /**
     * @var OrderSubjectReader
     */
    private $orderSubjectReader;

    /**
     * A constructor.
     *
     * @param OrderSubjectReader $orderSubjectReader
     */
    public function __construct(OrderSubjectReader $orderSubjectReader)
    {
        $this->orderSubjectReader = $orderSubjectReader;
    }

    /**
     * @inheritDoc
     *
     * @see \Magento\Payment\Gateway\Request\BuilderInterface::build()
     */
    public function build(array $buildSubject)
    {
        return [
            'body' => $this->createOrderItemsRequest($buildSubject),
        ];
    }

    /**
     * A function that create order request items.
     *
     * @param array $buildSubject
     *
     * @return array
     */
    private function createOrderItemsRequest(array $buildSubject)
    {
        $request = [
            'items' => [],
        ];

        /**
         * @var \Magento\Sales\Model\Order $order
         */
        $order = $this->readOrder($buildSubject);

        /**
         * @var \Hokodo\BNPL\Api\Data\OrderItemInterface $orderItem
         */
        foreach ($this->readOrderItems($buildSubject) as $orderItem) {
            $item = [
                'item_id' => $orderItem->getItemId(),
                'quantity' => $orderItem->getQuantity(),
                'total_amount' => $orderItem->getTotalAmount(),
                'tax_amount' => $orderItem->getTaxAmount(),
                'shipping_id' => '',
                'shipping_provider' => $order->getShippingDescription(),
            ];

            $request['items'][] = $item;
        }

        return $request;
    }

    /**
     * A function that read order information.
     *
     * @param array $buildSubject
     *
     * @return \Hokodo\BNPL\Api\Data\OrderInformationInterface
     */
    private function readOrderInformation(array $buildSubject)
    {
        return $this->orderSubjectReader->readOrder($buildSubject);
    }

    /**
     * A function that read order items.
     *
     * @param array $buildSubject
     *
     * @return \Hokodo\BNPL\Api\Data\OrderItemInterface[]
     */
    private function readOrderItems(array $buildSubject)
    {
        return $this->readOrderInformation($buildSubject)->getItems();
    }

    /**
     * A function that read shipment.
     *
     * @param array $buildSubject
     *
     * @return \Magento\Sales\Api\Data\ShipmentInterface
     */
    private function readShipment(array $buildSubject)
    {
        return $this->orderSubjectReader->readShipment($buildSubject);
    }

    /**
     * A function that read order.
     *
     * @param array $buildSubject
     *
     * @return \Magento\Sales\Model\Order
     */
    private function readOrder(array $buildSubject)
    {
        return $this->readShipment($buildSubject)->getOrder();
    }
}
