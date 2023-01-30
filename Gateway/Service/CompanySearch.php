<?php
/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Hokodo\BNPL\Gateway\Service;

use Hokodo\BNPL\Api\Data\Gateway\CompanySearchRequestInterface;
use Magento\Framework\Exception\NotFoundException;
use Magento\Payment\Gateway\Command\CommandException;
use Magento\Payment\Gateway\Command\ResultInterface;

class CompanySearch extends AbstractService
{
    /**
     * API search payment gateway command.
     *
     * @param CompanySearchRequestInterface $request
     *
     * @return ResultInterface
     *
     * @throws NotFoundException
     * @throws CommandException
     */
    public function search(CompanySearchRequestInterface $request): ResultInterface
    {
        return $this->commandPool->get('hokodo_company_search')->execute($request->__toArray());
    }
}