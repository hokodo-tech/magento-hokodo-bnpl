<?php

/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Api;

/**
 * Interface Hokodo\BNPL\Api\DeferredPaymentProcessorInterface.
 */
interface DeferredPaymentProcessorInterface
{
    /**
     * A function that makes process.
     *
     * @param string                               $created
     * @param Data\DeferredPaymentPayloadInterface $data
     *
     * @return bool
     */
    public function process($created, Data\DeferredPaymentPayloadInterface $data);
}
