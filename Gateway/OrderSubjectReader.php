<?php

/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Gateway;

use Hokodo\BNPL\Api\Data\OrderInformationInterface;
use Magento\Quote\Api\Data\CartInterface;
use Magento\Sales\Api\Data\ShipmentInterface;

/**
 * Class Hokodo\BNPL\Gateway\OrderSubjectReader.
 */
class OrderSubjectReader extends SubjectReader
{
    /**
     * A function that read cart.
     *
     * @param array $subject
     *
     * @throws \InvalidArgumentException
     *
     * @return \Magento\Quote\Api\Data\CartInterface
     */
    public function readCart(array $subject)
    {
        $cart = $this->readFieldValue('quote', $subject);

        if (!$cart instanceof CartInterface) {
            throw new \InvalidArgumentException(__('Cart should be provided'));
        }
        return $cart;
    }

    /**
     * A function that read shipment.
     *
     * @param array $subject
     *
     * @throws \InvalidArgumentException
     *
     * @return \Magento\Sales\Api\Data\ShipmentInterface
     */
    public function readShipment(array $subject)
    {
        $shipment = $this->readFieldValue('shipment', $subject);

        if (!$shipment instanceof ShipmentInterface) {
            throw new \InvalidArgumentException(__('Shipment should be provided'));
        }
        return $shipment;
    }

    /**
     * A function that read order.
     *
     * @param array $subject
     *
     * @throws \InvalidArgumentException
     *
     * @return \Hokodo\BNPL\Api\Data\OrderInformationInterface
     */
    public function readOrder(array $subject)
    {
        $order = $this->readFieldValue('orders', $subject);

        //      if (!$order instanceof OrderInformationInterface) {
        //      throw new \InvalidArgumentException('Order should be provided');
        //  }
        return $order;
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Gateway\SubjectReader::readEndpointParam()
     */
    public function readEndpointParam($param, array $subject)
    {
        if (!in_array($param, ['order_id', 'order_api_id'])) {
            throw new \InvalidArgumentException(__('For endpoint order param should be order_id or api_order_id'));
        }

        $backtrace = debug_backtrace(); // @codingStandardsIgnoreLine
        array_shift($backtrace);
        foreach ($backtrace as $key => $route) {
            $routeClass = $route['class'] ?? '';
            $routeType = $route['type'] ?? '';
            $routeFunction = $route['function'] ?? '';
            $routeFile = $route['file'] ?? '';
            $routeLine = $route['line'] ?? '';
            $backtrace[$key] = [
                'action' => $routeClass . $routeType . $routeFunction . '()',
                'file' => $routeFile . ':' . $routeLine,
            ];
        }
        if ($param == 'order_id') {
            return $this->readOrder($subject)->getId();
        }
        if ($param == 'order_api_id') {
            return $this->readOrder($subject)->getData('order_api_id');
        }
    }
}
