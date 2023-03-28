<?php
/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Gateway\Command\Result;

use Hokodo\BNPL\Api\Data\Company\CreditInterface;
use Magento\Payment\Gateway\Command\ResultInterface;

interface CompanyCreditResultInterface extends ResultInterface
{
    /**
     * A function that returns data model.
     *
     * @return CreditInterface
     */
    public function getDataModel(): CreditInterface;

    /**
     * A function that returns list of result.
     *
     * @return CreditInterface[]
     */
    public function getList(): array;
}
