<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Hokodo\BNPL\Model\Data\Gateway;

use Hokodo\BNPL\Api\Data\Gateway\PatchOrderRequestInterface;

class PatchOrderRequest extends CreateOrderRequest implements PatchOrderRequestInterface
{

    /**
     * @inheritDoc
     */
    public function setOrderId(string $orderId): PatchOrderRequestInterface
    {
        $this->setData(self::ORDER_ID, $orderId);
        return $this;
    }
}
