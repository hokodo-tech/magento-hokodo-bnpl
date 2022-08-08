<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Hokodo\BNPL\Api\Data\Gateway;

interface CreateOrganisationRequestInterface
{
    public const COMPANY_ID = 'comapny_id';
    public const UNIQUE_ID = 'unique_id';
    public const REGISTERED = 'registered';

    /**
     * @param string $companyId
     *
     * @return mixed
     */
    public function setCompanyId(string $companyId);

    /**
     * @param string $uniqueId
     *
     * @return $this
     */
    public function setUniqueId(string $uniqueId): self;

    /**
     * @param string $registered
     *
     * @return $this
     */
    public function setRegistered(string $registered): self;
}
