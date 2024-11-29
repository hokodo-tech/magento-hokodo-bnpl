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
     * Get query.
     *
     * @param string $table
     *
     * @return string
     */
    public function getIsNullQuery(string $table): string;

    /**
     * Get additional tables.
     *
     * @return array
     */
    public function getAdditionalTables(): array;

    /**
     * Get column name based on env.
     *
     * @param string $env
     *
     * @return string
     */
    public function getColumnName(string $env): string;

    /**
     * Get table alias based on env.
     *
     * @param string $env
     *
     * @return string
     */
    public function getTableAlias(string $env): string;
}
