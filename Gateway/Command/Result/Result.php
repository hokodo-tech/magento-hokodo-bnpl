<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Gateway\Command\Result;

use Magento\Framework\Api\DataObjectHelper;
use Magento\Payment\Gateway\Command\ResultInterface;

/**
 * Class Hokodo\BNPL\Gateway\Command\Result\Result.
 */
class Result implements ResultInterface
{
    /**
     * @var DataObjectHelper
     */
    protected $dataObjectHelper;

    /**
     * @var array
     */
    protected $result;

    /**
     * @var \Magento\Framework\DataObject
     */
    protected $dataModel;

    /**
     * @var array
     */
    protected $list = [];

    /**
     * @param DataObjectHelper $dataObjectHelper
     * @param array            $result
     */
    public function __construct(
        DataObjectHelper $dataObjectHelper,
        array $result = []
    ) {
        $this->dataObjectHelper = $dataObjectHelper;
        $this->result = $result;
    }

    /**
     * @inheritDoc
     *
     * @see \Magento\Payment\Gateway\Command\ResultInterface::get()
     */
    public function get()
    {
        return $this->result;
    }

    /**
     * A function that returns data model.
     *
     * @return \Magento\Framework\DataObject
     */
    public function getDataModel()
    {
        if (null == $this->dataModel) {
            $this->populateDataModel();
        }
        return $this->dataModel;
    }

    /**
     * A function that returns list of result.
     *
     * @return array
     */
    public function getList()
    {
        if (empty($this->list)) {
            $this->populateListResult();
        }
        return $this->list;
    }

    /**
     * A function that returns populate data model.
     *
     * @return \Hokodo\BNPL\Gateway\Command\Result\Result
     */
    protected function populateDataModel()
    {
        return $this;
    }

    /**
     * A function that returns populate list result.
     *
     * @return \Hokodo\BNPL\Gateway\Command\Result\Result
     */
    protected function populateListResult()
    {
        return $this;
    }
}
