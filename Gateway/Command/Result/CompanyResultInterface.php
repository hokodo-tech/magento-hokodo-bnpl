<?php
/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Gateway\Command\Result;

use Hokodo\BNPL\Api\Data\CompanyInterface;
use Magento\Payment\Gateway\Command\ResultInterface;

interface CompanyResultInterface extends ResultInterface
{
    /**
     * A function that returns data model.
     *
     * @return CompanyInterface
     */
    public function getDataModel(): CompanyInterface;

    /**
     * A function that returns list of result.
     *
     * @return CompanyInterface[]
     */
    public function getList(): array;
}
