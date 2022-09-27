<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
// @codingStandardsIgnoreFile

declare(strict_types=1);

namespace Hokodo\BNPL\Api\Data\Webapi;

interface OfferRequestInterface
{
    public const COMPANY_ID = 'company_id';

    /**
     * Company id getter.
     *
     * @return string
     */
    public function getCompanyId(): string;

    /**
     * Company id setter.
     *
     * @param string $companyId
     *
     * @return $this
     */
    public function setCompanyId(string $companyId): self;
}
