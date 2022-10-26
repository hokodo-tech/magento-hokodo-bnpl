<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Hokodo\BNPL\Model\Config\Source;

use Magento\Customer\Api\GroupManagementInterface;

class CustomerGroups implements \Magento\Framework\Data\OptionSourceInterface
{
    /**
     * @var GroupManagementInterface
     */
    private GroupManagementInterface $groupManagement;

    /**
     * @var \Magento\Framework\Convert\DataObject
     */
    private \Magento\Framework\Convert\DataObject $converter;

    /**
     * @param GroupManagementInterface              $groupManagement
     * @param \Magento\Framework\Convert\DataObject $converter
     */
    public function __construct(
        GroupManagementInterface $groupManagement,
        \Magento\Framework\Convert\DataObject $converter
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
