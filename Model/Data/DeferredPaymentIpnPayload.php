<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Model\Data;

use Hokodo\BNPL\Api\Data\DeferredPaymentIpnPayloadInterface;
use Magento\Framework\Api\AbstractSimpleObject;

/**
 * Class Hokodo\BNPL\Model\Data\DeferredPaymentIpnPayload.
 */
class DeferredPaymentIpnPayload extends AbstractSimpleObject implements DeferredPaymentIpnPayloadInterface
{
    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\DeferredPaymentIpnPayloadInterface::setOrder()
     */
    public function setOrder(array $order)
    {
        return $this->setData(self::ORDER, $order);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\DeferredPaymentIpnPayloadInterface::getOrder()
     */
    public function getOrder()
    {
        return $this->_get(self::ORDER);
    }
}
