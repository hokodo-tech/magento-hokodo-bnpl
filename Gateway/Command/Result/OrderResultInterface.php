<?php
/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Gateway\Command\Result;

use Hokodo\BNPL\Api\Data\OrderInterface;
use Magento\Payment\Gateway\Command\ResultInterface;

interface OrderResultInterface extends ResultInterface
{
    /**
     * A function that returns data model.
     *
     * @return OrderInterface
     */
    public function getDataModel(): OrderInterface;

    /**
     * A function that returns list of result.
     *
     * @return OrderInterface[]
     */
    public function getList(): array;
}
