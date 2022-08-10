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
    public function createOrder(CreateOrderRequestInterface $createOrderRequest): ?ResultInterface
    {
        return $this->commandPool->get('sdk_order_create')->execute($createOrderRequest->__toArray());
    }

    public function patchOrder(CreateOrderRequestInterface $createOrderRequest): ?ResultInterface
    {
        return $this->commandPool->get('sdk_order_patch')->execute($createOrderRequest->__toArray());
    }
}
