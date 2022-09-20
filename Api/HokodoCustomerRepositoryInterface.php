<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Hokodo\BNPL\Api;

use Hokodo\BNPL\Api\Data\HokodoCustomerInterface;

interface HokodoCustomerRepositoryInterface
{
    /**
     * Save entity.
     *
     * @param HokodoCustomerInterface $hokodoCustomer
     *
     * @return HokodoCustomerInterface
     */
    public function save(HokodoCustomerInterface $hokodoCustomer): HokodoCustomerInterface;

    /**
     * Delete entity.
     *
     * @param HokodoCustomerInterface $hokodoCustomer
     *
     * @return bool
     */
    public function delete(HokodoCustomerInterface $hokodoCustomer): bool;

    /**
     * Retrieve Hokodo Data Webapi by magento customer id.
     *
     * @param int $customerId
     *
     * @return HokodoCustomerInterface
     */
    public function getByCustomerId(int $customerId): HokodoCustomerInterface;
}
