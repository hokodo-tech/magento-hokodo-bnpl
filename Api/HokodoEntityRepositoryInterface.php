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
     * Get HokodoEntityInterface model by magento customer it is linked to.
     *
     * @param int $customerId
     *
     * @return HokodoEntityInterface
     */
    public function getByCustomerId(int $customerId): HokodoEntityInterface;

    /**
     * Get HokodoEntityInterface model by magento entity id it is linked to.
     *
     * @param int $entityId
     *
     * @return HokodoEntityInterface
     */
    public function getByEntityId(int $entityId): HokodoEntityInterface;
}
