<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Hokodo\BNPL\Api\Webapi;

use Hokodo\BNPL\Api\Data\Webapi\CreateOrderRequestInterface;
use Hokodo\BNPL\Api\Data\Webapi\CreateOrderResponseInterface;

interface OrderInterface
{
    /**
     * Create order request webapi handler.
     *
     * @param CreateOrderRequestInterface $payload
     *
     * @return CreateOrderResponseInterface
     */
    public function create(CreateOrderRequestInterface $payload): CreateOrderResponseInterface;
}
