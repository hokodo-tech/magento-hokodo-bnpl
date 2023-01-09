<?php

/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Gateway\Request;

use Hokodo\BNPL\Gateway\OrderSubjectReader;
use Magento\Payment\Gateway\Request\BuilderInterface;

/**
 * Class Hokodo\BNPL\Gateway\Request\OrderUpdater.
 */
class OrderUpdater implements BuilderInterface
{
    /**
     * @var OrderSubjectReader
     */
    private $orderSubjectReader;

    /**
     * @param OrderSubjectReader $orderSubjectReader
     */
    public function __construct(OrderSubjectReader $orderSubjectReader)
    {
        $this->orderSubjectReader = $orderSubjectReader;
    }

    /**
     * @inheritDoc
     *
     * @see \Magento\Payment\Gateway\Request\BuilderInterface::build()
     */
    public function build(array $buildSubject)
    {
        $buildSubject['orders'] = $buildSubject['order'];
        $order = $this->orderSubjectReader->readOrder($buildSubject);
        return [
            'body' => ['unique_id' => $order->getIncrementId()],
        ];
    }
}
