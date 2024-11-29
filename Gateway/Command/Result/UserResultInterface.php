<?php

/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Gateway\Command\Result;

use Hokodo\BNPL\Api\Data\UserInterface;
use Magento\Payment\Gateway\Command\ResultInterface;

interface UserResultInterface extends ResultInterface
{
    /**
     * A function that returns data model.
     *
     * @return UserInterface
     */
    public function getDataModel(): UserInterface;

    /**
     * A function that returns list of result.
     *
     * @return UserInterface[]
     */
    public function getList(): array;
}
