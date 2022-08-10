<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Hokodo\BNPL\Gateway\Service;

use Hokodo\BNPL\Api\Data\Gateway\CreateOrderRequestInterface;
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
     * @param CreateOrderRequestInterface $createOrderRequest
     *
     * @return ResultInterface|null
     *
     * @throws \Magento\Framework\Exception\NotFoundException
     * @throws \Magento\Payment\Gateway\Command\CommandException
     */
    public function patchOrder(CreateOrderRequestInterface $createOrderRequest): ?ResultInterface
    {
        return $this->commandPool->get('sdk_order_patch')->execute($createOrderRequest->__toArray());
    }
}
