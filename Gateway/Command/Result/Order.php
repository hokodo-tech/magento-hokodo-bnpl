<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Gateway\Command\Result;

use Hokodo\BNPL\Api\Data\OrderInformationInterface;
use Hokodo\BNPL\Api\Data\OrderInformationInterfaceFactory;
use Magento\Framework\Api\DataObjectHelper;

/**
 * Class Hokodo\BNPL\Gateway\Command\Result\Order.
 */
class Order extends Result
{
    /**
     * @var OrderInformationInterfaceFactory
     */
    private $dataFactory;

    /**
     * @param OrderInformationInterfaceFactory $dataFactory
     * @param DataObjectHelper                 $dataObjectHelper
     * @param array                            $result
     */
    public function __construct(
        OrderInformationInterfaceFactory $dataFactory,
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
                OrderInformationInterface::class
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
                    OrderInformationInterface::class
                );

                $this->list[] = $order;
            }
        }
        return parent::populateListResult();
    }
}
