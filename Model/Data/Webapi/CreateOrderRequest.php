<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Hokodo\BNPL\Model\Data\Webapi;

use Hokodo\BNPL\Api\Data\Webapi\CreateOrderRequestInterface;
use Magento\Framework\DataObject;

class CreateOrderRequest extends DataObject implements CreateOrderRequestInterface
{
    /**
     * @inheritDoc
     */
    public function getOrganisationId(): string
    {
        return $this->getData(self::ORGANISATION_ID);
    }

    /**
     * @inheritDoc
     */
    public function setOrganisationId(string $organisationId): self
    {
        $this->setData(self::ORGANISATION_ID, $organisationId);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getUserId(): string
    {
        return $this->getData(self::USER_ID);
    }

    /**
     * @inheritDoc
     */
    public function setUserId(string $userId): self
    {
        $this->setData(self::USER_ID, $userId);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getQuoteId(): string
    {
        return $this->getData(self::QUOTE_ID);
    }

    /**
     * @inheritDoc
     */
    public function setQuoteId(string $quoteId): self
    {
        $this->setData(self::QUOTE_ID, $quoteId);
        return $this;
    }
}
