<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Gateway\Command\Result;

use Hokodo\BNPL\Api\Data\DeferredPaymentInterface;
use Hokodo\BNPL\Api\Data\DeferredPaymentInterfaceFactory;
use Magento\Framework\Api\DataObjectHelper;

/**
 * Class Hokodo\BNPL\Gateway\Command\Result\DeferredPayment.
 */
class DeferredPayment extends Result
{
    /**
     * @var DeferredPaymentInterfaceFactory
     */
    private $dataFactory;

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

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Gateway\Command\Result\Result::populateListResult()
     */
    protected function populateListResult()
    {
        $data = $this->get();
        $this->list = [];

        if (isset($data['results']) && is_array($data['results'])) {
            foreach ($data['results'] as $orderData) {
                $order = $this->dataFactory->create();
                $this->dataObjectHelper->populateWithArray(
                    $order,
                    $orderData,
                    DeferredPaymentInterface::class
                );

                $this->list[] = $order;
            }
        }
        return parent::populateListResult();
    }
}
