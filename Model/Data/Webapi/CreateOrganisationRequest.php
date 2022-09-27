<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
// @codingStandardsIgnoreFile

declare(strict_types=1);

namespace Hokodo\BNPL\Model\Data\Webapi;

use Hokodo\BNPL\Api\Data\Webapi\CreateOrganisationRequestInterface;
use Magento\Framework\DataObject;

class CreateOrganisationRequest extends DataObject implements CreateOrganisationRequestInterface
{
    /**
     * @inerhitdoc
     */
    public function getCompanyId(): string
    {
        return $this->getData(self::COMPANY_ID);
    }

    /**
     * @inerhitdoc
     */
    public function setCompanyId(string $companyId): self
    {
        $this->setData(self::COMPANY_ID, $companyId);
        return $this;
    }

    /**
     * @inerhitdoc
     */
    public function setQuoteId(string $quoteId): self
    {
        $this->setData(self::QUOTE_ID, $quoteId);
        return $this;
    }

    /**
     * @inerhitdoc
     */
    public function getQuoteId(): string
    {
        return $this->getData(self::QUOTE_ID);
    }
}
