<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Gateway\Command\Result;

use Hokodo\BNPL\Api\Data\OrganisationUserInterface;
use Hokodo\BNPL\Api\Data\OrganisationUserInterfaceFactory;
use Magento\Framework\Api\DataObjectHelper;

/**
 * Class Hokodo\BNPL\Gateway\Command\Result\UserOrganisation.
 */
class UserOrganisation extends Result
{
    /**
     * @var OrganisationUserInterface
     */
    private $dataFactory;

    /**
     * @param OrganisationUserInterfaceFactory $dataFactory
     * @param DataObjectHelper                 $dataObjectHelper
     * @param array                            $result
     */
    public function __construct(
        OrganisationUserInterfaceFactory $dataFactory,
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
                OrganisationUserInterface::class
            );
        }

        return parent::populateDataModel();
    }
}
