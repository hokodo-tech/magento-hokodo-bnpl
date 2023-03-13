<?php
/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Hokodo\BNPL\Plugin\Customer\Model\ResourceModel\Grid;

use Hokodo\BNPL\Service\Customer\Grid\QueryConfigInterface;
use Magento\Customer\Model\ResourceModel\Grid\Collection;
use Magento\Framework\DB\Select;

class CollectionPlugin
{
    public const FIELD_NAME = 'is_hokodo_company_assigned';

    /**
     * @var QueryConfigInterface
     */
    private QueryConfigInterface $queryConfig;

    /**
     * @param QueryConfigInterface $queryConfig
     */
    public function __construct(
        QueryConfigInterface $queryConfig
    ) {
        $this->queryConfig = $queryConfig;
    }

    /**
     * Before loadWithFilter plugin.
     *
     * @param Collection $collection
     * @param bool       $printQuery
     * @param bool       $logQuery
     *
     * @return array
     */
    public function beforeLoadWithFilter(
        Collection $collection,
        bool $printQuery = false,
        bool $logQuery = false
    ) {
        foreach ($this->getAdditionalTables() as $tableName => $tableParam) {
            $this->joinAdditionalTable(
                $collection->getSelect(),
                $collection->getTable($tableName),
                $tableParam['alias'],
                $tableParam['condition'],
                $tableParam['columns']
            );
        }

        return [$printQuery, $logQuery];
    }

    /**
     * Add Data to Filter.
     *
     * @param Collection        $collection
     * @param callable          $proceed
     * @param string|array      $field
     * @param string|array|null $condition
     *
     * @return Collection
     */
    public function aroundAddFieldToFilter(
        Collection $collection,
        callable $proceed,
        $field,
        $condition = null
    ): Collection {
        if ($field === self::FIELD_NAME) {
            foreach ($this->getAdditionalTables() as $tableName => $tableParam) {
                $this->joinAdditionalTable(
                    $collection->getSelect(),
                    $collection->getTable($tableName),
                    $tableParam['alias'],
                    $tableParam['condition'],
                    $tableParam['columns']
                );
            }

            $isNullQuery = $this->getIsNullQuery();
            $conditionSql = $collection->getConnection()->prepareSqlCondition($isNullQuery, $condition);
            $collection->getSelect()->where($conditionSql);
            return $collection;
        }
        return $proceed($field, $condition);
    }

    /**
     * Get query.
     *
     * @return string
     */
    public function getIsNullQuery(): string
    {
        return $this->queryConfig->getIsNullQuery();
    }

    /**
     * Get additional tables.
     *
     * @return array
     */
    private function getAdditionalTables(): array
    {
        return $this->queryConfig->getAdditionalTables();
    }

    /**
     * Join additional table to select.
     *
     * @param Select $select
     * @param string $tableName
     * @param string $tableAlias
     * @param string $condition
     * @param array  $columns
     *
     * @return void
     */
    private function joinAdditionalTable(
        Select $select,
        string $tableName,
        string $tableAlias,
        string $condition,
        array $columns
    ) {
        $usedTables = array_column($select->getPart(Select::FROM), 'tableName');
        if (!\in_array($tableName, $usedTables, true)) {
            $select->joinLeft([$tableAlias => $tableName], $condition, $columns);
        }
    }
}
