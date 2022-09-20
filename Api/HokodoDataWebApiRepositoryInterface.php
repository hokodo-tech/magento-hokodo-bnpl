<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Hokodo\BNPL\Api;

use Hokodo\BNPL\Api\Data\HokodoDataWebApiInterface;

interface HokodoDataWebApiRepositoryInterface
{
    /**
     * Save entity.
     *
     * @param HokodoDataWebApiInterface $hokodoDataWebApi
     *
     * @return HokodoDataWebApiInterface
     */
    public function save(HokodoDataWebApiInterface $hokodoDataWebApi): HokodoDataWebApiInterface;

    /**
     * Delete entity.
     *
     * @param HokodoDataWebApiInterface $hokodoDataWebApi
     *
     * @return bool
     */
    public function delete(HokodoDataWebApiInterface $hokodoDataWebApi): bool;

    /**
     * Retrieve Hokodo Data Webapi by magento customer id.
     *
     * @param int $customerId
     *
     * @return HokodoDataWebApiInterface
     */
    public function getByCustomerId(int $customerId): HokodoDataWebApiInterface;
}
