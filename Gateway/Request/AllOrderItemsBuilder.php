<?php
/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Gateway\Request;

use Hokodo\BNPL\Gateway\OrderSubjectReader;
use Magento\Payment\Gateway\Request\BuilderInterface;

/**
 * Class Hokodo\BNPL\Gateway\Request\AllOrderItemsBuilder.
 */
class AllOrderItemsBuilder implements BuilderInterface
{
    /**
     * @var OrderSubjectReader
     */
    private $orderSubjectReader;

    /**
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
     * A function that create order items request.
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
         * @var \Hokodo\BNPL\Api\Data\OrderItemInterface $orderItem
         */
        foreach ($this->readOrderItems($buildSubject) as $orderItem) {
            $item = [
                'item_id' => $orderItem->getItemId(),
                'quantity' => $orderItem->getQuantity(),
                'total_amount' => $orderItem->getTotalAmount(),
                'tax_amount' => $orderItem->getTaxAmount(),
            ];

            if ($orderItem->getUnitPrice() !== null) {
                $item['unit_price'] = $orderItem->getUnitPrice();
            }

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
}
