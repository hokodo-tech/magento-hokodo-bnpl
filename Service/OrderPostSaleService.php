<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Service;

use Hokodo\BNPL\Api\Data\OrderInformationInterface;
use Magento\Sales\Api\Data\ShipmentInterface;

/**
 * Class Hokodo\BNPL\Service\OrderPostSaleService.
 */
class OrderPostSaleService extends AbstractService
{
    /**
     * A function that checks fulfill.
     *
     * @param OrderInformationInterface $order
     * @param ShipmentInterface         $shipment
     *
     * @return OrderInformationInterface
     */
    public function fulfill(OrderInformationInterface $order, ShipmentInterface $shipment)
    {
        return $this->executeCommand('order_fulfill', [
            'orders' => $order,
            'shipment' => $shipment,
        ])->getDataModel();
    }

    /**
     * A function that amend order.
     *
     * @param OrderInformationInterface $order
     *
     * @return OrderInformationInterface
     */
    public function amend(OrderInformationInterface $order)
    {
        return $this->executeCommand('order_amend', [
            'orders' => $order,
        ])->getDataModel();
    }

    /**
     * A function that cancel order.
     *
     * @param OrderInformationInterface $order
     *
     * @return OrderInformationInterface
     */
    public function cancel(OrderInformationInterface $order)
    {
        return $this->executeCommand('order_cancel', [
            'orders' => $order,
        ])->getDataModel();
    }

    /**
     * A function that return order.
     *
     * @param OrderInformationInterface $order
     *
     * @return OrderInformationInterface
     */
    public function return(OrderInformationInterface $order)
    {
        return $this->executeCommand('order_return', [
            'orders' => $order,
        ])->getDataModel();
    }

    /**
     * A function that supports discount.
     *
     * @param OrderInformationInterface $order
     *
     * @return OrderInformationInterface
     */
    public function discount(OrderInformationInterface $order)
    {
        return $this->executeCommand('order_discount', [
            'orders' => $order,
        ])->getDataModel();
    }

    /**
     * A function that do mark disputed.
     *
     * @return \Hokodo\BNPL\Service\OrderPostSaleService
     */
    public function markDisputed()
    {
        return $this;
    }
}
