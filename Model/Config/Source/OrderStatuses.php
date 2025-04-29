<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Hokodo\BNPL\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;
use Magento\Sales\Model\ResourceModel\Status\Collection;

class OrderStatuses implements OptionSourceInterface
{
    /**
     * @var Collection
     */
    private Collection $statusCollection;

    /**
     * @param Collection $statusCollection
     */
    public function __construct(
        Collection $statusCollection
    ) {
        $this->statusCollection = $statusCollection;
    }

    /**
     * @inheritDoc
     */
    public function toOptionArray()
    {
        return array_map(static function ($status) {
            return [
                'value' => $status->getState(),
                'label' => $status->getLabel(),
            ];
        }, $this->statusCollection->getItems());
    }
}
