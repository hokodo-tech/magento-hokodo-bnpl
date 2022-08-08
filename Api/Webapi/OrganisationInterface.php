<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Hokodo\BNPL\Api\Webapi;

use Hokodo\BNPL\Api\Data\Webapi\CreateOrganisationRequestInterface;
use Hokodo\BNPL\Api\Data\Webapi\CreateOrganisationResponseInterface;

interface OrganisationInterface
{
    /**
     * @param CreateOrganisationRequestInterface $payload
     *
     * @return CreateOrganisationResponseInterface
     */
    public function create(
        CreateOrganisationRequestInterface $payload
    ): CreateOrganisationResponseInterface;
}
