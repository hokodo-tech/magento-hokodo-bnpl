<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Service;

use Hokodo\BNPL\Api\Data\CompanyInterface;

/**
 * Class Hokodo\BNPL\Service\CompanyService.
 */
class CompanyService extends AbstractService
{
    /**
     * A function that search company name or country name.
     *
     * @param string $companyName
     * @param string $countryName
     *
     * @return CompanyInterface[]
     */
    public function search($companyName, $countryName)
    {
        return $this->executeCommand(
            'company_search',
            [
                'name' => $companyName,
                'country' => $countryName,
            ]
        )->getList();
    }

    /**
     * A function that returns view of company.
     *
     * @param CompanyInterface $company
     *
     * @return CompanyInterface
     */
    public function get(CompanyInterface $company)
    {
        return $this->executeCommand(
            'company_view',
            [
                'companies' => $company,
            ]
        )->getDataModel();
    }
}
