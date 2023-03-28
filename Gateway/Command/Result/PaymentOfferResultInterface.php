<?php
/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Gateway\Command\Result;

use Hokodo\BNPL\Api\Data\PaymentOffersInterface;
use Magento\Payment\Gateway\Command\ResultInterface;

interface PaymentOfferResultInterface extends ResultInterface
{
    /**
     * A function that returns data model.
     *
     * @return PaymentOffersInterface
     */
    public function getDataModel(): PaymentOffersInterface;

    /**
     * A function that returns list of result.
     *
     * @return PaymentOffersInterface[]
     */
    public function getList(): array;
}
