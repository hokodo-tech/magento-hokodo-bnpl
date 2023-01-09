<?php
/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Hokodo\BNPL\Api;

use Magento\Payment\Gateway\Data\OrderAdapterInterface;

interface OrderAdapterReaderInterface
{
    /**
     * Get order from subject.
     *
     * @param array $subject
     *
     * @return OrderAdapterInterface
     */
    public function getOrderAdapter(array $subject): OrderAdapterInterface;
}
