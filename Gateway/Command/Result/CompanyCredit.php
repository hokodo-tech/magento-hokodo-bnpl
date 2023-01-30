<?php

declare(strict_types=1);

namespace Hokodo\BNPL\Gateway\Command\Result;

use Hokodo\BNPL\Api\Data\Company\CreditInterface;
use Hokodo\BNPL\Api\Data\Company\CreditInterfaceFactory;
use Magento\Framework\Api\DataObjectHelper;

class CompanyCredit extends Result
{
    /**
     * @var CreditInterfaceFactory
     */
    private $dataFactory;

    /**
     * @param CreditInterfaceFactory $dataFactory
     * @param DataObjectHelper       $dataObjectHelper
     * @param array                  $result
     */
    public function __construct(
        CreditInterfaceFactory $dataFactory,
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
        if (isset($data[CreditInterface::COMPANY])) {
            $this->dataObjectHelper->populateWithArray(
                $this->dataModel,
                $data,
                CreditInterface::class
            );
        }

        return parent::populateDataModel();
    }
}
