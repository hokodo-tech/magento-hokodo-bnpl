<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Hokodo\BNPL\Gateway\Service;

use Hokodo\BNPL\Api\Data\Gateway\CreateOrderRequestInterface;
use Hokodo\BNPL\Api\Data\Gateway\PatchOrderRequestInterface;
use Magento\Payment\Gateway\Command\ResultInterface;

class Order extends AbstractService
{
    /**
     * Create Order gateway command.
     *
     * @param CreateOrderRequestInterface $createOrderRequest
     *
     * @return ResultInterface|null
     *
     * @throws \Magento\Framework\Exception\NotFoundException
     * @throws \Magento\Payment\Gateway\Command\CommandException
     */
    public function createOrder(CreateOrderRequestInterface $createOrderRequest): ?ResultInterface
    {
        return $this->commandPool->get('sdk_order_create')->execute($createOrderRequest->__toArray());
    }

    /**
     * Patch order gateway command.
     *
     * @param PatchOrderRequestInterface $createOrderRequest
     *
     * @return ResultInterface|null
     *
     * @throws \Magento\Framework\Exception\NotFoundException
     * @throws \Magento\Payment\Gateway\Command\CommandException
     */
    public function patchOrder(PatchOrderRequestInterface $createOrderRequest): ?ResultInterface
    {
        return $this->commandPool->get('sdk_order_patch')->execute($createOrderRequest->__toArray());
    }

    /**
     * Get order gateway command.
     *
     * @param array $getOrderRequest
     *
     * @return ResultInterface|null
     *
     * @throws \Magento\Framework\Exception\NotFoundException
     * @throws \Magento\Payment\Gateway\Command\CommandException
     */
    public function getOrder($getOrderRequest): ?ResultInterface
    {
        return $this->commandPool->get('sdk_order_get')->execute($getOrderRequest);
    }
}
