<?php
/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Gateway\Command\Result;

use Magento\Framework\Api\AbstractSimpleObject;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\ObjectManagerInterface;
use Magento\Payment\Gateway\Command\ResultInterface;

/**
 * Class Hokodo\BNPL\Gateway\Command\Result\Result.
 */
class Result implements ResultInterface
{
    /**
     * @var string
     */
    private string $itemFieldId = 'id';

    /**
     * @var string
     */
    private string $listFieldId = 'results';
    /**
     * @var DataObjectHelper
     */
    private DataObjectHelper $dataObjectHelper;

    /**
     * @var ObjectManagerInterface
     */
    private ObjectManagerInterface $objectManager;

    /**
     * @var array
     */
    private array $result;

    /**
     * @var AbstractSimpleObject
     */
    private AbstractSimpleObject $dataModel;

    /**
     * @var array
     */
    private array $list = [];

    /**
     * @var string|null
     */
    private ?string $dataInterface;

    /**
     * @param ObjectManagerInterface $objectManager
     * @param DataObjectHelper       $dataObjectHelper
     * @param array                  $result
     * @param string|null            $dataInterface
     * @param string|null            $itemFieldId
     * @param string|null            $listFieldId
     */
    public function __construct(
        ObjectManagerInterface $objectManager,
        DataObjectHelper $dataObjectHelper,
        array $result = [],
        string $dataInterface = null,
        string $itemFieldId = null,
        string $listFieldId = null
    ) {
        $this->objectManager = $objectManager;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataInterface = $dataInterface;
        $this->result = $result;
        if ($itemFieldId) {
            $this->itemFieldId = $itemFieldId;
        }
        if ($listFieldId) {
            $this->listFieldId = $listFieldId;
        }
    }

    /**
     * @inheritDoc
     *
     * @see \Magento\Payment\Gateway\Command\ResultInterface::get()
     */
    public function get(): array
    {
        return $this->result;
    }

    /**
     * A function that returns data model.
     *
     * @return AbstractSimpleObject
     */
    public function getDataModel(): AbstractSimpleObject
    {
        if (empty($this->dataModel)) {
            $this->populateDataModel();
        }
        return $this->dataModel;
    }

    /**
     * A function that returns list of result.
     *
     * @return array
     */
    public function getList(): array
    {
        if (empty($this->list)) {
            $this->populateListResult();
        }
        return $this->list;
    }

    /**
     * A function that returns populate data model.
     *
     * @return void
     */
    private function populateDataModel(): void
    {
        if ($this->itemFieldId && $this->dataInterface) {
            $this->dataModel = $this->objectManager->create($this->dataInterface);
            $data = $this->get();
            if (isset($data[$this->itemFieldId])) {
                $this->dataObjectHelper->populateWithArray(
                    $this->dataModel,
                    $data,
                    $this->dataInterface
                );
            }
        }
    }

    /**
     * A function that returns populate list result.
     *
     * @return void
     */
    private function populateListResult(): void
    {
        if ($this->itemFieldId && $this->dataInterface) {
            $data = $this->get();
            $this->list = [];

            if (isset($data[$this->listFieldId]) && is_array($data[$this->listFieldId])) {
                foreach ($data[$this->listFieldId] as $userData) {
                    $user = $this->objectManager->create($this->dataInterface);
                    $this->dataObjectHelper->populateWithArray(
                        $user,
                        $userData,
                        $this->dataInterface
                    );

                    $this->list[] = $user;
                }
            }
        }
    }
}
