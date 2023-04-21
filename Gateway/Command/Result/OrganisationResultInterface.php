<?php
/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Gateway\Command\Result;

use Hokodo\BNPL\Api\Data\OrganisationInterface;
use Magento\Payment\Gateway\Command\ResultInterface;

interface OrganisationResultInterface extends ResultInterface
{
    /**
     * A function that returns data model.
     *
     * @return OrganisationInterface
     */
    public function getDataModel(): OrganisationInterface;

    /**
     * A function that returns list of result.
     *
     * @return OrganisationInterface[]
     */
    public function getList(): array;
}
