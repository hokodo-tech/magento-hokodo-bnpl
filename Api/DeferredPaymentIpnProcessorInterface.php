<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Api;

/**
 * Interface Hokodo\BNPL\Api\DeferredPaymentIpnProcessorInterface.
 */
interface DeferredPaymentIpnProcessorInterface
{
    /**
     * A function that makes process.
     *
     * @param string                                                   $created
     * @param \Hokodo\BNPL\Api\Data\DeferredPaymentIpnPayloadInterface $data
     *
     * @return bool
     */
    public function process($created, Data\DeferredPaymentIpnPayloadInterface $data);
}
