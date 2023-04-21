<?php
/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Hokodo\BNPL\Gateway\Service;

use Hokodo\BNPL\Api\Data\Gateway\CompanySearchRequestInterface;
use Hokodo\BNPL\Gateway\Command\Result\CompanyResultInterface;
use Magento\Framework\Exception\NotFoundException;
use Magento\Payment\Gateway\Command\CommandException;

class CompanySearch extends AbstractService
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
}
