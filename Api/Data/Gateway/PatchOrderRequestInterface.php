<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Hokodo\BNPL\Api\Data\Gateway;

interface PatchOrderRequestInterface
{
    public const ORDER_ID = 'order_id';

    /**
     * Order Id setter.
     *
     * @param string $orderId
     *
     * @return $this
     */
    public function setOrderId(string $orderId): self;
}
