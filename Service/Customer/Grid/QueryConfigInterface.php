<?php
/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Hokodo\BNPL\Service\Customer\Grid;

interface QueryConfigInterface
{
    /**
     * Get additional tables.
     *
     * @return array
     */
    public function getAdditionalTables(): array;
}
