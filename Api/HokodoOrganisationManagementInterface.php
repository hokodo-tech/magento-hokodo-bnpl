<?php
/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Api;

use Hokodo\BNPL\Api\Data\HokodoOrganisationInterface;

/**
 * Interface Hokodo\BNPL\Api\HokodoOrganisationManagementInterface.
 */
interface HokodoOrganisationManagementInterface
{
    /**
     * A function that get user organisation.
     *
     * @param string $organisationApiId
     *
     * @return HokodoOrganisationInterface
     */
    public function getUserOrganisation($organisationApiId);
}
