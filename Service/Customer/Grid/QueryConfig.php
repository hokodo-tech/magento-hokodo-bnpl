<?php
/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Hokodo\BNPL\Service\Customer\Grid;

class QueryConfig implements QueryConfigInterface
{
    /**
     * Get query.
     *
     * @return string
     */
    public function getIsNullQuery(): string
    {
        return '!ISNULL(`hokodo_entity_table`.`company_id`)';
    }

    /**
     * Get additional tables.
     *
     * @return array
     */
    public function getAdditionalTables(): array
    {
        return [
            'hokodo_customer' => [
                'alias' => 'hokodo_entity_table',
                'condition' => 'main_table.entity_id = hokodo_entity_table.customer_id',
                'columns' => [
                    'hokodo_company_id' => 'hokodo_entity_table.company_id',
                    'is_hokodo_company_assigned' => new \Zend_Db_Expr($this->getIsNullQuery()),
                ],
            ],
        ];
    }
}
