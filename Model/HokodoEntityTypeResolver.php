<?php

/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Hokodo\BNPL\Model;

use Hokodo\BNPL\Api\HokodoEntityTypeResolverInterface;
use Hokodo\BNPL\Model\Config\Source\EntityLevelForSave;

class HokodoEntityTypeResolver implements HokodoEntityTypeResolverInterface
{
    /**
     * Get Entity Type.
     *
     * @return string
     */
    public function resolve(): string
    {
        return EntityLevelForSave::CUSTOMER;
    }
}
