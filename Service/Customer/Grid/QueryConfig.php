<?php

/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Hokodo\BNPL\Service\Customer\Grid;

use Hokodo\BNPL\Gateway\Config\Config;
use Hokodo\BNPL\Model\Config\Source\Env;
use Hokodo\BNPL\Model\ResourceModel\HokodoCustomer;
use Hokodo\BNPL\Model\ResourceModel\HokodoCustomerDev;
use Magento\Framework\Exception\LocalizedException;

class QueryConfig implements QueryConfigInterface
{
    /**
     * @var HokodoCustomer
     */
    private HokodoCustomer $hokodoCustomerResource;

    /**
     * @var HokodoCustomerDev
     */
    private HokodoCustomerDev $hokodoCustomerDevResource;

    /**
     * @var Env
     */
    private Env $env;

    /**
     * @param HokodoCustomer    $hokodoCustomerResource
     * @param HokodoCustomerDev $hokodoCustomerDevResource
     * @param Env               $env
     */
    public function __construct(
        HokodoCustomer $hokodoCustomerResource,
        HokodoCustomerDev $hokodoCustomerDevResource,
        Env $env
    ) {
        $this->hokodoCustomerResource = $hokodoCustomerResource;
        $this->hokodoCustomerDevResource = $hokodoCustomerDevResource;
        $this->env = $env;
    }

    /**
     * Get query.
     *
     * @param string $table
     *
     * @return string
     */
    public function getIsNullQuery(string $table): string
    {
        return sprintf('!ISNULL(`%s`.`%s`)', $table, 'company_id');
    }

    /**
     * Get additional tables.
     *
     * @return array
     *
     * @throws LocalizedException
     */
    public function getAdditionalTables(): array
    {
        $tables = [];
        foreach ($this->env->getEnvMap() as $env => $envId) {
            $tableAlias = $this->getTableAlias($env);
            $tables[$tableAlias] = [
                'name' => $this->getTableName($env),
                'condition' => $this->getCondition($env),
                'columns' => [
                   $this->getColumnName($env) => new \Zend_Db_Expr($this->getIsNullQuery($tableAlias)),
                ],
            ];
        }

        return $tables;
    }

    /**
     * Get table name based on env.
     *
     * @param string $tableAlias
     *
     * @return string
     *
     * @throws LocalizedException
     */
    private function getTableName(string $tableAlias): string
    {
        return $tableAlias === Config::ENV_PRODUCTION ?
            $this->hokodoCustomerResource->getMainTable() :
            $this->hokodoCustomerDevResource->getMainTable();
    }

    /**
     * Get table alias based on env.
     *
     * @param string $env
     *
     * @return string
     */
    public function getTableAlias(string $env): string
    {
        return 'hokodo_t_' . $env;
    }

    /**
     * Get condition based on env.
     *
     * @param string $env
     *
     * @return string
     */
    private function getCondition(string $env): string
    {
        if ($env === Config::ENV_PRODUCTION) {
            return sprintf('main_table.entity_id = %s.customer_id', $this->getTableAlias($env));
        }
        return sprintf(
            'main_table.entity_id = %s.customer_id AND %s.env = %s',
            $this->getTableAlias($env),
            $this->getTableAlias($env),
            $this->env->getEnvId($env)
        );
    }

    /**
     * Get column name based on env.
     *
     * @param string $env
     *
     * @return string
     */
    public function getColumnName(string $env): string
    {
        return 'hokodo_' . $env;
    }
}
