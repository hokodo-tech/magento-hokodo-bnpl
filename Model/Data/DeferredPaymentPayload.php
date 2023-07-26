<?php
/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Model\Data;

use Hokodo\BNPL\Api\Data\DeferredPaymentPayloadInterface;
use Magento\Framework\Api\AbstractSimpleObject;

/**
 * Class Hokodo\BNPL\Model\Data\DeferredPaymentPayload.
 */
class DeferredPaymentPayload extends AbstractSimpleObject implements DeferredPaymentPayloadInterface
{
    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\DeferredPaymentPayloadInterface::setOrder()
     */
    public function setOrder(array $order)
    {
        return $this->setData(self::ORDER, $order);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\DeferredPaymentPayloadInterface::getOrder()
     */
    public function getOrder()
    {
        return $this->_get(self::ORDER);
    }
}
