<?php
/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Hokodo\BNPL\Api;

use Hokodo\BNPL\Api\Data\HokodoEntityInterface;

interface HokodoEntityRepositoryInterface
{
    /**
     * Retrieve Hokodo Data Webapi by magento customer id.
     *
     * @param int $customerId
     *
     * @return HokodoEntityInterface
     */
    public function getByCustomerId(int $customerId): HokodoEntityInterface;
}
