<?php
/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Hokodo\BNPL\Gateway\Service;

use Hokodo\BNPL\Api\Data\Gateway\CreateOrderRequestInterface;
use Hokodo\BNPL\Api\Data\Gateway\PatchOrderRequestInterface;
use Hokodo\BNPL\Gateway\Command\Result\OrderResultInterface;

class Order extends AbstractService
{
    /**
     * Create Order gateway command.
     *
     * @param CreateOrderRequestInterface $createOrderRequest
     *
     * @return OrderResultInterface|null
     *
     * @throws \Magento\Framework\Exception\NotFoundException
     * @throws \Magento\Payment\Gateway\Command\CommandException
     */
    public function createOrder(CreateOrderRequestInterface $createOrderRequest)
    {
        return $this->commandPool->get('order_create')->execute($createOrderRequest->__toArray());
    }

    /**
     * Patch order gateway command.
     *
     * @param PatchOrderRequestInterface $createOrderRequest
     *
     * @return OrderResultInterface|null
     *
     * @throws \Magento\Framework\Exception\NotFoundException
     * @throws \Magento\Payment\Gateway\Command\CommandException
     */
    public function patchOrder(PatchOrderRequestInterface $createOrderRequest)
    {
        return $this->commandPool->get('order_patch')->execute($createOrderRequest->__toArray());
    }

    /**
     * Get order gateway command.
     *
     * @param array $getOrderRequest
     *
     * @return OrderResultInterface|null
     *
     * @throws \Magento\Framework\Exception\NotFoundException
     * @throws \Magento\Payment\Gateway\Command\CommandException
     */
    public function getOrder($getOrderRequest)
    {
        return $this->commandPool->get('order_get')->execute($getOrderRequest);
    }
}
