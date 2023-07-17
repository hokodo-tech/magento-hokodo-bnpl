<?php
/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Hokodo\BNPL\Setup\Patch\Data;

use Hokodo\BNPL\Api\Data\HokodoCustomerInterface;
use Hokodo\BNPL\Model\ResourceModel\HokodoCustomer\Collection;
use Magento\Framework\DB\Select;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Psr\Log\LoggerInterface;

class RemoveDuplicateRecords implements DataPatchInterface
{
    /**
     * @var \Magento\Framework\DB\Adapter\AdapterInterface
     */
    private \Magento\Framework\DB\Adapter\AdapterInterface $connection;

    /**
     * @var string|null
     */
    private ?string $mainTable;

    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * @param Collection      $collection
     * @param LoggerInterface $logger
     */
    public function __construct(
        Collection $collection,
        LoggerInterface $logger
    ) {
        $this->connection = $collection->getConnection();
        $this->mainTable = $collection->getMainTable();
        $this->logger = $logger;
    }

    /**
     * @inheritDoc
     */
    public static function getDependencies()
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public function getAliases()
    {
        return [];
    }

    /**
     * @inheritDoc
     *
     * @throws \Zend_Db_Statement_Exception
     */
    public function apply()
    {
        $duplicateCustomers = $this->connection->query($this->getDuplicateCustomersSql())->fetchAll();

        foreach ($duplicateCustomers as $duplicateCustomer) {
            $customerRecords = $this->connection
                ->query($this->getCustomerDuplicateRecordsSql($duplicateCustomer[HokodoCustomerInterface::CUSTOMER_ID]))
                ->fetchAll();
            array_pop($customerRecords);

            $customerIds = array_map(
                function ($customerRecord) {
                    return $customerRecord[HokodoCustomerInterface::ID];
                },
                $customerRecords
            );

            try {
                $this->connection->beginTransaction();
                $this->connection->query($this->getDeleteDuplicatesSql($customerIds));
                $this->connection->commit();
            } catch (\Exception $exception) {
                $this->logger->warning(
                    __(
                        'There was an error when deleting duplicates for customer: %s. Changes has been rolled back.',
                        $duplicateCustomer[HokodoCustomerInterface::CUSTOMER_ID]
                    )
                );
                $this->connection->rollBack();
            }
        }
    }

    /**
     * Get sql for selecting unique duplicate customers.
     *
     * @return \Magento\Framework\DB\Select
     */
    private function getDuplicateCustomersSql(): Select
    {
        return $this->connection->select()
            ->from($this->mainTable)
            ->reset('columns')
            ->columns([
                HokodoCustomerInterface::CUSTOMER_ID,
                'count' => sprintf('COUNT(%s)', HokodoCustomerInterface::CUSTOMER_ID),
            ])
            ->having('count > 1')
            ->group(HokodoCustomerInterface::CUSTOMER_ID);
    }

    /**
     * Get sql for duplicate records of single customer.
     *
     * @param string $duplicateCustomer
     *
     * @return Select
     */
    public function getCustomerDuplicateRecordsSql(string $duplicateCustomer): Select
    {
        $customerRecordsSql = $this->connection->select()
            ->from($this->mainTable)
            ->reset('columns')
            ->columns(['id'])
            ->where(sprintf(
                '%s = %s',
                HokodoCustomerInterface::CUSTOMER_ID,
                $duplicateCustomer
            ));
        return $customerRecordsSql;
    }

    /**
     * Get sql for deletion of duplicate customers.
     *
     * @param array $customerIds
     *
     * @return string
     */
    public function getDeleteDuplicatesSql(array $customerIds): string
    {
        $deleteDuplicatesSql = $this->connection->select()
            ->from($this->mainTable)
            ->where(
                sprintf(
                    '%s IN (%s)',
                    HokodoCustomerInterface::ID,
                    implode(',', $customerIds)
                )
            )
            ->deleteFromSelect($this->mainTable);
        return $deleteDuplicatesSql;
    }
}
