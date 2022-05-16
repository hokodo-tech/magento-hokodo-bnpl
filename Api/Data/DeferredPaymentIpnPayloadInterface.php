<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Api\Data;

/**
 * Interface Hokodo\BNPL\Api\Data\DeferredPaymentIpnPayloadInterface.
 */
interface DeferredPaymentIpnPayloadInterface
{
    public const ORDER = 'order';

    /**
     * A function that sets order.
     *
     * @param \Hokodo\BNPL\Api\Data\OrderIpnInterface $order
     *
     * @return $this;
     */
    public function setOrder(OrderIpnInterface $order);

    /**
     * A function that gets order.
     *
     * @return \Hokodo\BNPL\Api\Data\OrderIpnInterface
     */
    public function getOrder();
}
