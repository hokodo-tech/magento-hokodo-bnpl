<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Hokodo\BNPL\Gateway\Command\Result;

use Hokodo\BNPL\Api\Data\PostSaleEventInterface;
use Hokodo\BNPL\Api\Data\PostSaleEventInterfaceFactory;
use Magento\Framework\Api\DataObjectHelper;

class PostSaleEvent extends Result
{
    private PostSaleEventInterfaceFactory $dataFactory;

    public function __construct(
        PostSaleEventInterfaceFactory $dataFactory,
        DataObjectHelper $dataObjectHelper,
        array $result = []
    ) {
        parent::__construct(
            $dataObjectHelper,
            $result
        );
        $this->dataFactory = $dataFactory;
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Gateway\Command\Result\Result::populateDataModel()
     */
    protected function populateDataModel()
    {
        $this->dataModel = $this->dataFactory->create();

        $data = $this->get();
        $data['metadata']['test_key'] = 'test_value';
        if (isset($data['created'])) {
            $this->dataObjectHelper->populateWithArray(
                $this->dataModel,
                $data,
                PostSaleEventInterface::class
            );
        }

        return parent::populateDataModel();
    }
}
