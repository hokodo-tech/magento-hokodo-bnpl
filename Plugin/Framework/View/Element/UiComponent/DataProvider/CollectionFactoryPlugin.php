<?php
/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Hokodo\BNPL\Plugin\Framework\View\Element\UiComponent\DataProvider;

use Magento\Framework\Data\Collection;
use Magento\Framework\View\Element\UiComponent\DataProvider\CollectionFactory;

class CollectionFactoryPlugin
{
    public const FIELD_NAME = 'is_hokodo_company_assigned';

    /**
     * Add data to grid.
     *
     * @param CollectionFactory $subject
     * @param Collection        $result
     * @param string            $requestName
     *
     * @return Collection
     */
    public function afterGetReport(
        CollectionFactory $subject,
        Collection $result,
        string $requestName
    ): Collection {
        if ($requestName == 'customer_listing_data_source') {
            $field = self::FIELD_NAME;
            $isNullQuery = '!ISNULL(`customer_id`)';
            $expression = "{$isNullQuery} as {$field}";

            $result->getSelect()->joinLeft(
                ['hokodo_customer_table' => $result->getTable('hokodo_customer')],
                'main_table.entity_id = hokodo_customer_table.customer_id',
                ['company_id' => 'company_id']
            )->columns(new \Zend_Db_Expr($expression));
        }
        return $result;
    }
}
