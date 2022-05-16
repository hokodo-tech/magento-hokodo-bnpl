<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Api;

/**
 * Interface Hokodo\BNPL\Api\CompanySearchServiceInterface.
 */
interface CompanySearchServiceInterface
{
    /**
     * A function that search.
     *
     * @param string $companyName
     * @param string $countryName
     *
     * @return \Hokodo\BNPL\Api\Data\HokodoOrganisationInterface[]
     */
    public function search($companyName, $countryName);
}
