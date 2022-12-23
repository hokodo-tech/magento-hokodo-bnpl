<?php

/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Hokodo\BNPL\Model;

use Hokodo\BNPL\Api\HokodoEntityResolverInterface;
use Hokodo\Bnpl\Gateway\Config\Config;

class HokodoEntityResolver implements HokodoEntityResolverInterface
{
    /**
     * Get Entity Type.
     *
     * @return string
     */
    public function getEntityType(): string
    {
        return Config::HOKODO_ENTITY_FOR_SAVE_COMPANY_LEVEL_IN_CUSTOMER;
    }
}
