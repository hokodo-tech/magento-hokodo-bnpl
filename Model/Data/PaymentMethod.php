<?php

/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Model\Data;

use Hokodo\BNPL\Api\Data\PaymentMethodInterface;
use Magento\Framework\Api\AbstractSimpleObject;

/**
 * Class Hokodo\BNPL\Model\Data\PaymentMethod.
 */
class PaymentMethod extends AbstractSimpleObject implements PaymentMethodInterface
{
    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\PaymentMethodInterface::getType()
     */
    public function getType()
    {
        return $this->_get(self::TYPE);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\PaymentMethodInterface::setType()
     */
    public function setType($type)
    {
        return $this->setData(self::TYPE, $type);
    }
}
