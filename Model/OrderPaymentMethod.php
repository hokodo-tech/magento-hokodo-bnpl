<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Hokodo\BNPL\Model;

use Magento\Framework\Api\ExtensionAttribute\JoinDataInterfaceFactory;
use Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface;
use Magento\Framework\Api\SearchCriteria\CollectionProcessor\JoinProcessor\CustomJoinInterface;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\Data\OrderPaymentInterface;

class OrderPaymentMethod implements CustomJoinInterface
{
    public const TABLE_ALIAS = 'sales_order_payment_table';

    /**
     * @var JoinDataInterfaceFactory
     */
    private $joinDataFactory;

    /**
     * @var JoinProcessorInterface
     */
    private $joinProcessor;

    /**
     * OrderTransaction constructor.
     *
     * @param JoinDataInterfaceFactory $joinDataFactory
     * @param JoinProcessorInterface   $joinProcessor
     */
    public function __construct(
        JoinDataInterfaceFactory $joinDataFactory,
        JoinProcessorInterface $joinProcessor
    ) {
        $this->joinDataFactory = $joinDataFactory;
        $this->joinProcessor = $joinProcessor;
    }

    /**
     * @inheritDoc
     */
    public function apply(AbstractDb $collection)
    {
        $joinData = $this->joinDataFactory->create();
        $joinData->setJoinField(OrderInterface::ENTITY_ID)
            ->setReferenceTable($collection->getResource()->getTable('sales_order_payment'))
            ->setReferenceField(OrderPaymentInterface::PARENT_ID)
            ->setReferenceTableAlias(self::TABLE_ALIAS)
            ->setSelectFields([]);
        $collection->joinExtensionAttribute($joinData, $this->joinProcessor);
    }
}
