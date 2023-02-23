<?php
/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Gateway\Command\Result;

use Hokodo\BNPL\Api\Data\DeferredPaymentInterface;
use Hokodo\BNPL\Api\Data\DeferredPaymentInterfaceFactory;
use Magento\Framework\Api\DataObjectHelper;

class DeferredPayment extends Result
{
    /**
     * @var DeferredPaymentInterfaceFactory
     */
    private DeferredPaymentInterfaceFactory $dataFactory;

    /**
     * @param DeferredPaymentInterfaceFactory $dataFactory
     * @param DataObjectHelper                $dataObjectHelper
     * @param array                           $result
     */
    public function __construct(
        DeferredPaymentInterfaceFactory $dataFactory,
        DataObjectHelper $dataObjectHelper,
        array $result = []
    ) {
        parent::__construct($dataObjectHelper, $result);
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
        if (isset($data['id'])) {
            $this->dataObjectHelper->populateWithArray(
                $this->dataModel,
                $data,
                DeferredPaymentInterface::class
            );
        }
        return parent::populateDataModel();
    }
}
