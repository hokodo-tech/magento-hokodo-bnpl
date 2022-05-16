<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Service;

use Hokodo\BNPL\Api\Data\OrderInformationInterface;
use Hokodo\BNPL\Api\Data\OrganisationInterface;
use Hokodo\BNPL\Api\Data\UserInterface;
use Magento\Quote\Api\Data\CartInterface;
use Magento\Sales\Api\Data\OrderInterface;

/**
 * Class Hokodo\BNPL\Service\OrderService.
 */
class OrderService extends AbstractService
{
    /**
     * A function that creates the order.
     *
     * @param CartInterface         $quote
     * @param UserInterface         $user
     * @param OrganisationInterface $organisation
     *
     * @return \Hokodo\BNPL\Api\Data\OrderInformationInterface
     */
    public function create(
        CartInterface $quote,
        UserInterface $user,
        OrganisationInterface $organisation
    ) {
        return $this->executeCommand('order_create', [
            'quote' => $quote,
            'users' => $user,
            'organisations' => $organisation,
        ])->getDataModel();
    }

    /**
     * A function that update order.
     *
     * @param OrderInterface $order
     *
     * @return \Magento\Framework\DataObject
     */
    public function update(
        OrderInterface $order
    ) {
        return $this->executeCommand('order_update', [
            'order' => $order, 'orders' => $order,
        ])->getDataModel();
    }

    /**
     * A function that returns view of order.
     *
     * @param string $order
     *
     * @return \Hokodo\BNPL\Api\Data\OrderInformationInterface
     */
    public function get(OrderInformationInterface $order)
    {
        return $this->executeCommand('order_view', [
            'orders' => $order,
        ])->getDataModel();
    }

    /**
     * A function that returns list of orders.
     *
     * @return \Hokodo\BNPL\Api\Data\OrderInformationInterface[]
     */
    public function getList()
    {
        return $this->executeCommand('order_list', [])->getList();
    }

    /**
     * A function that removes an order.
     *
     * @param OrderInformationInterface $order
     *
     * @return \Hokodo\BNPL\Gateway\Command\Result\Result
     */
    public function delete(OrderInformationInterface $order)
    {
        return $this->executeCommand('order_delete', [
            'orders' => $order,
        ]);
    }
}
