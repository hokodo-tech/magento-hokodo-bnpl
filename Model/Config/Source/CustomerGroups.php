<?php

/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Hokodo\BNPL\Model\Config\Source;

use Magento\Customer\Api\GroupManagementInterface;
use Magento\Framework\Convert\DataObject;

class CustomerGroups implements \Magento\Framework\Data\OptionSourceInterface
{
    /**
     * @var GroupManagementInterface
     */
    private GroupManagementInterface $groupManagement;

    /**
     * @var DataObject
     */
    private DataObject $converter;

    /**
     * @param GroupManagementInterface $groupManagement
     * @param DataObject               $converter
     */
    public function __construct(
        GroupManagementInterface $groupManagement,
        DataObject $converter
    ) {
        $this->groupManagement = $groupManagement;
        $this->converter = $converter;
    }

    /**
     * @inheritDoc
     */
    public function toOptionArray()
    {
        $groups = $this->groupManagement->getLoggedInGroups();
        $groups[] = $this->groupManagement->getNotLoggedInGroup();
        return $this->converter->toOptionArray(
            $groups,
            'id',
            'code'
        );
    }
}
