<?php
/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Gateway\Command\Result;

use Hokodo\BNPL\Api\Data\OrganisationInterface;
use Hokodo\BNPL\Api\Data\OrganisationInterfaceFactory;
use Magento\Framework\Api\DataObjectHelper;

/**
 * Class Hokodo\BNPL\Gateway\Command\Result$Organisation.
 */
class Organisation extends Result
{
    /**
     * @var OrganisationInterfaceFactory
     */
    private $dataFactory;

    /**
     * @param OrganisationInterfaceFactory $dataFactory
     * @param DataObjectHelper             $dataObjectHelper
     * @param array                        $result
     */
    public function __construct(
        OrganisationInterfaceFactory $dataFactory,
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
                OrganisationInterface::class
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
            foreach ($data['results'] as $userData) {
                $user = $this->dataFactory->create();
                $this->dataObjectHelper->populateWithArray(
                    $user,
                    $userData,
                    OrganisationInterface::class
                );

                $this->list[] = $user;
            }
        }

        return parent::populateListResult();
    }

    /**
     * A function that returns data model.
     *
     * @return OrganisationInterface
     */
    public function getDataModel(): OrganisationInterface
    {
        return parent::getDataModel();
    }
}
