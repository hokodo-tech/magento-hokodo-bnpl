<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Model\Data;

use Hokodo\BNPL\Api\Data\AllowedPaymentMethodInterface;
use Magento\Framework\Api\AbstractSimpleObject;

/**
 * Class Hokodo\BNPL\Model\Data$AllowedPaymentMethod.
 */
class AllowedPaymentMethod extends AbstractSimpleObject implements AllowedPaymentMethodInterface
{
    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\AllowedPaymentMethodInterface::getType()
     */
    public function getType()
    {
        return $this->_get(self::TYPE);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\AllowedPaymentMethodInterface::setType()
     */
    public function setType($type)
    {
        return $this->setData(self::TYPE, $type);
    }
}
