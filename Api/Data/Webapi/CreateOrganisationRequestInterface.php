<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Hokodo\BNPL\Api\Data\Webapi;

interface CreateOrganisationRequestInterface
{
    public const COMPANY_ID = 'company_id';
    public const QUOTE_ID = 'quote_id';

    /**
     * @return string
     */
    public function getCompanyId(): string;

    /**
     * @param string $companyId
     *
     * @return $this
     */
    public function setCompanyId(string $companyId): self;

    /**
     * @param string $quoteId
     *
     * @return $this
     */
    public function setQuoteId(string $quoteId): self;

    /**
     * @return string
     */
    public function getQuoteId(): string;
}
