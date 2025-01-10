<?php

/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Gateway\Command\Result;

use Hokodo\BNPL\Api\Data\PostSaleEventInterface;
use Magento\Payment\Gateway\Command\ResultInterface;

interface PostSaleEventResultInterface extends ResultInterface
{
    /**
     * A function that returns data model.
     *
     * @return PostSaleEventInterface
     */
    public function getDataModel(): PostSaleEventInterface;

    /**
     * A function that returns list of result.
     *
     * @return PostSaleEventInterface[]
     */
    public function getList(): array;
}
