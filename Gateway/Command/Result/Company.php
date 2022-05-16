<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Gateway\Command\Result;

use Hokodo\BNPL\Api\Data\CompanyInterface;
use Hokodo\BNPL\Api\Data\CompanyInterfaceFactory;
// use Magento\Payment\Gateway\Command\Result\ArrayResult;
use Magento\Framework\Api\DataObjectHelper;

/**
 * Class Hokodo\BNPL\Gateway\Command\Result\Company.
 */
class Company extends Result
{
    /**
     * @var CompanyInterfaceFactory
     */
    private $dataFactory;

    /**
     * @param CompanyInterfaceFactory $dataFactory
     * @param DataObjectHelper        $dataObjectHelper
     * @param array                   $result
     */
    public function __construct(
        CompanyInterfaceFactory $dataFactory,
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
                CompanyInterface::class
            );
        }
        return parent::populateDataModel();
    }

    /**
     * A function that returns populate list result.
     *
     * @return \Hokodo\BNPL\Gateway\Command\Result\Result
     */
    protected function populateListResult()
    {
        $searchResultData = $this->get();
        if (isset($searchResultData['matches']) && is_array($searchResultData['matches'])) {
            foreach ($searchResultData['matches'] as $searchCompany) {
                /**
                 * @var CompanyInterface $company
                 */
                $company = $this->dataFactory->create();
                $this->dataObjectHelper->populateWithArray(
                    $company,
                    $searchCompany,
                    CompanyInterface::class
                );
                $this->list[] = $company;
            }
        }

        return parent::populateListResult();
    }
}
