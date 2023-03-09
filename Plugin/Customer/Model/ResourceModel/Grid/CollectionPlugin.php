<?php
/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Hokodo\BNPL\Plugin\Customer\Model\ResourceModel\Grid;

use Hokodo\BNPL\Plugin\Framework\View\Element\UiComponent\DataProvider\CollectionFactoryPlugin;
use Magento\Customer\Model\ResourceModel\Grid\Collection;

class CollectionPlugin
{
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
        if ($field === CollectionFactoryPlugin::FIELD_NAME) {
            $isNullQuery = '!ISNULL(`customer_id`)';

            $conditionSql = $collection->getConnection()->prepareSqlCondition($isNullQuery, $condition);

            $collection->getSelect()->where($conditionSql);

            return $collection;
        }
        return $proceed($field, $condition);
    }
}
