<?php

/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Api\Data;

/**
 * Interface Hokodo\BNPL\Api\Data\DeferredPaymentPayloadInterface.
 */
interface DeferredPaymentPayloadInterface
{
    public const ORDER = 'order';

    /**
     * A function that sets order.
     *
     * @param mixed $order
     *
     * @return $this;
     */
    public function setOrder(array $order);

    /**
     * A function that gets order.
     *
     * @return mixed
     */
    public function getOrder();
}
