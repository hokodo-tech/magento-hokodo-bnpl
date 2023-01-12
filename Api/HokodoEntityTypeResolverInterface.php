<?php
/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Hokodo\BNPL\Api;

interface HokodoEntityTypeResolverInterface
{
    /**
     * Get Entity Type.
     *
     * @return string
     */
    public function resolve(): string;
}
