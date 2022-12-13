<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
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
     * @param \Hokodo\BNPL\Api\Data\Webapi\HokodoCustomerRequestInterface $payload
     *
     * @return \Hokodo\BNPL\Api\Data\Webapi\HokodoCustomerResponseInterface
     */
    public function assignCompany(HokodoCustomerRequestInterface $payload): HokodoCustomerResponseInterface;
}
