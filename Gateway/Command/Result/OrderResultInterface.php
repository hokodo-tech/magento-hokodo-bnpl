<?php
/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Gateway\Command\Result;

use Hokodo\BNPL\Api\Data\OrderInformationInterface;
use Magento\Payment\Gateway\Command\ResultInterface;

interface OrderResultInterface extends ResultInterface
{
    /**
     * A function that returns data model.
     *
     * @return OrderInformationInterface
     */
    public function getDataModel(): OrderInformationInterface;

    /**
     * A function that returns list of result.
     *
     * @return OrderInformationInterface[]
     */
    public function getList(): array;
}
