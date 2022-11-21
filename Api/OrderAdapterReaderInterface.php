<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Hokodo\BNPL\Api;

use Magento\Payment\Gateway\Data\OrderAdapterInterface;

interface OrderAdapterReaderInterface
{
    public function getOrderAdapter(array $subject): OrderAdapterInterface;
}
