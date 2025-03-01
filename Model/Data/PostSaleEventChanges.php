<?php

/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Hokodo\BNPL\Model\Data;

use Hokodo\BNPL\Api\Data\PostSaleEventChangesInterface;
use Magento\Framework\Api\AbstractSimpleObject;

class PostSaleEventChanges extends AbstractSimpleObject implements PostSaleEventChangesInterface
{
    /**
     * @inheritdoc
     */
    public function getAuthorisation(): int
    {
        return $this->_get(self::AUTHORISATION);
    }

    /**
     * @inheritdoc
     */
    public function setAuthorisation(int $authorisation): self
    {
        $this->setData(self::AUTHORISATION, $authorisation);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getProtectedCaptures(): int
    {
        return $this->_get(self::PROTECTED_CAPTURES);
    }

    /**
     * @inheritdoc
     */
    public function setProtectedCaptures(int $protectedCaptures): self
    {
        $this->setData(self::PROTECTED_CAPTURES, $protectedCaptures);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getUnprotectedCaptures(): int
    {
        return $this->_get(self::UNPROTECTED_CAPTURES);
    }

    /**
     * @inheritdoc
     */
    public function setUnprotectedCaptures(int $unprotectedCaptures): self
    {
        $this->setData(self::UNPROTECTED_CAPTURES, $unprotectedCaptures);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getRefunds(): int
    {
        return $this->_get(self::REFUNDS);
    }

    /**
     * @inheritdoc
     */
    public function setRefunds(int $refunds): self
    {
        $this->setData(self::REFUNDS, $refunds);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getVoidedAuthorisation(): int
    {
        return $this->_get(self::VOIDED_AUTHORISATION);
    }

    /**
     * @inheritdoc
     */
    public function setVoidedAuthorisation(int $voidedAuthorisation): self
    {
        $this->setData(self::VOIDED_AUTHORISATION, $voidedAuthorisation);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getExpiredAuthorisation(): int
    {
        return $this->_get(self::EXPIRED_AUTHORISATION);
    }

    /**
     * @inheritdoc
     */
    public function setExpiredAuthorisation(int $expiredAuthorisation): self
    {
        $this->setData(self::EXPIRED_AUTHORISATION, $expiredAuthorisation);
        return $this;
    }
}
