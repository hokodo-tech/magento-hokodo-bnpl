<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Gateway\Command\Result;

use Hokodo\BNPL\Api\Data\UserInterface;
use Hokodo\BNPL\Api\Data\UserInterfaceFactory;
use Magento\Framework\Api\DataObjectHelper;

/**
 * Class Hokodo\BNPL\Gateway\Command\Result\User.
 */
class User extends Result
{
    /**
     * @var UserInterfaceFactory
     */
    private $dataFactory;

    /**
     * @param UserInterfaceFactory $dataFactory
     * @param DataObjectHelper     $dataObjectHelper
     * @param array                $result
     */
    public function __construct(
        UserInterfaceFactory $dataFactory,
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
                UserInterface::class
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
                    UserInterface::class
                );

                $this->list[] = $user;
            }
        }
        return parent::populateListResult();
    }
}
