<?php

/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Hokodo\BNPL\Api\Webapi;

use Hokodo\BNPL\Api\Data\Webapi\HokodoCustomerRequestInterface;
use Hokodo\BNPL\Api\Data\Webapi\HokodoCustomerResponseInterface;

interface HokodoCustomerInterface
{
    /**
     * Assign Hokodo(companyId, organisationId, userId) to a magento customer.
     *
     * @param HokodoCustomerRequestInterface $payload
     *
     * @return HokodoCustomerResponseInterface
     */
    public function assignCompany(HokodoCustomerRequestInterface $payload): HokodoCustomerResponseInterface;
}
