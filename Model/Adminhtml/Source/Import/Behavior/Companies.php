<?php
/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Hokodo\BNPL\Model\Adminhtml\Source\Import\Behavior;

use Magento\ImportExport\Model\Import;
use Magento\ImportExport\Model\Source\Import\AbstractBehavior;

class Companies extends AbstractBehavior
{
    /**
     * Prepare and return array of option values.
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            Import::BEHAVIOR_APPEND => __('Add'),
            Import::BEHAVIOR_REPLACE => __('Add/Replace'),
        ];
    }

    /**
     * Get current behaviour group code.
     *
     * @return string
     */
    public function getCode(): string
    {
        return 'hokodo_companies';
    }

    /**
     * Get array of notes for possible values.
     *
     * @param string $entityCode
     *
     * @return array
     */
    public function getNotes($entityCode): array
    {
        $messages = ['hokodo_companies' => [
            Import::BEHAVIOR_APPEND => __(
                'Add new Companies'
            ),
            Import::BEHAVIOR_REPLACE => __(
                'Add new Companies and Replace Existed Companies.'
            ),
        ]];
        return $messages[$entityCode] ?? [];
    }
}
