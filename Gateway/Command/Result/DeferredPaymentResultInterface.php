<?php

/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Gateway\Command\Result;

use Hokodo\BNPL\Api\Data\DeferredPaymentInterface;
use Magento\Payment\Gateway\Command\ResultInterface;

interface DeferredPaymentResultInterface extends ResultInterface
{
    /**
     * A function that returns data model.
     *
     * @return DeferredPaymentInterface
     */
    public function getDataModel(): DeferredPaymentInterface;

    /**
     * A function that returns list of result.
     *
     * @return DeferredPaymentInterface[]
     */
    public function getList(): array;
}
