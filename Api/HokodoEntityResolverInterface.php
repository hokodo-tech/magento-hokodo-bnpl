<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Hokodo\BNPL\Api;

interface HokodoEntityResolverInterface
{
    /**
     * Get Entity Type
     *
     * @return string
     */
    public function getEntityType(): string;
}