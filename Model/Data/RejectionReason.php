<?php

/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Model\Data;

use Hokodo\BNPL\Api\Data\RejectionReasonInterface;
use Magento\Framework\Api\AbstractSimpleObject;

/**
 * Class Hokodo\BNPL\Model\Data\RejectionReason.
 */
class RejectionReason extends AbstractSimpleObject implements RejectionReasonInterface
{
    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\RejectionReasonInterface::setCode()
     */
    public function setCode($code)
    {
        return $this->setData(self::CODE, $code);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\RejectionReasonInterface::getCode()
     */
    public function getCode()
    {
        return $this->_get(self::CODE);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\RejectionReasonInterface::setDetail()
     */
    public function setDetail($detail)
    {
        return $this->setData(self::DETAIL, $detail);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\RejectionReasonInterface::getDetail()
     */
    public function getDetail()
    {
        return $this->_get(self::DETAIL);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\RejectionReasonInterface::setParams()
     */
    public function setParams(array $params)
    {
        return $this->setData(self::PARAMS, $params);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\RejectionReasonInterface::getParams()
     */
    public function getParams()
    {
        return $this->_get(self::PARAMS);
    }
}
