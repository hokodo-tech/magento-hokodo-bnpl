<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
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
     * @param int $entityId
     *
     * @return HokodoEntityInterface
     */
    public function getById(int $entityId): HokodoEntityInterface;
}
