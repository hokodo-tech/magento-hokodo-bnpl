<?php

/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Hokodo\BNPL\Ui\Component\Listing\Column;

use Hokodo\BNPL\Model\Config\Source\Env;
use Hokodo\BNPL\Service\Customer\Grid\QueryConfigInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;

class IsCompanyAttached extends Column
{
    /**
     * @var array
     */
    private array $columnNames = [];

    /**
     * @var Env
     */
    private Env $env;

    /**
     * @param ContextInterface     $context
     * @param QueryConfigInterface $queryConfig
     * @param Env                  $env
     * @param UiComponentFactory   $uiComponentFactory
     * @param array                $components
     * @param array                $data
     */
    public function __construct(
        ContextInterface $context,
        QueryConfigInterface $queryConfig,
        Env $env,
        UiComponentFactory $uiComponentFactory,
        array $components = [],
        array $data = []
    ) {
        parent::__construct(
            $context,
            $uiComponentFactory,
            $components,
            $data
        );

        foreach ($queryConfig->getAdditionalTables() as $table) {
            $columnNames = array_keys($table['columns']);
            $this->columnNames[] = reset($columnNames);
        }
        $this->env = $env;
    }

    /**
     * Prepare Data.
     *
     * @param array $dataSource
     *
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                $envStatus = '';
                foreach ($this->columnNames as $columnName) {
                    $envStatus .= sprintf(
                        '<div style="white-space: nowrap;"><strong>%s:</strong> %s</div>',
                        $this->getColumnEnvTitle($columnName),
                        $item[$columnName] ? 'Yes' : 'No'
                    );
                }
                $item[$this->getData('name')] = $envStatus;
            }
        }

        return $dataSource;
    }

    /**
     * Get Title for Env.
     *
     * @param string $columnName
     *
     * @return string
     */
    private function getColumnEnvTitle(string $columnName): string
    {
        foreach ($this->env->getEnvMap() as $name => $value) {
            if (str_contains($columnName, $name)) {
                return ucfirst($name);
            }
        }
    }
}
