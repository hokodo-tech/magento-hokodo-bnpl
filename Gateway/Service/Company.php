<?php

/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Hokodo\BNPL\Gateway\Service;

use Hokodo\BNPL\Api\Data\Gateway\CompanyCreditRequestInterface;
use Hokodo\BNPL\Api\Data\Gateway\CompanySearchRequestInterface;
use Hokodo\BNPL\Gateway\Command\Result\CompanyCreditResultInterface;
use Hokodo\BNPL\Gateway\Command\Result\CompanyResultInterface;
use Magento\Framework\Exception\NotFoundException;
use Magento\Payment\Gateway\Command\CommandException;

class Company extends AbstractService
{
    /** @todo join Company and CompanySearch */
    /**
     * API search payment gateway command.
     *
     * @param CompanySearchRequestInterface $request
     *
     * @return CompanyResultInterface
     *
     * @throws NotFoundException
     * @throws CommandException
     */
    public function search(CompanySearchRequestInterface $request)
    {
        return $this->commandPool->get('hokodo_company_search')->execute($request->__toArray());
    }

    /**
     * Get company Credit object from Hokodo API.
     *
     * @param CompanyCreditRequestInterface $request
     *
     * @return CompanyCreditResultInterface
     *
     * @throws NotFoundException
     * @throws CommandException
     */
    public function getCredit(CompanyCreditRequestInterface $request)
    {
        return $this->commandPool->get('hokodo_company_credit')->execute($request->__toArray());
    }
}
