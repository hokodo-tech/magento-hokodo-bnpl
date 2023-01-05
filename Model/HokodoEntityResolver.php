<?php

/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Hokodo\BNPL\Model;

use Hokodo\BNPL\Api\HokodoEntityResolverInterface;
use Hokodo\BNPL\Model\Config\Source\EntityLevelForSave;

class HokodoEntityResolver implements HokodoEntityResolverInterface
{
    /**
     * Get Entity Type.
     *
     * @return string
     */
    public function getEntityType(): string
    {
        return EntityLevelForSave::CUSTOMER;
    }
}
