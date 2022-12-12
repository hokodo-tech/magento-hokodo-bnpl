<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Hokodo\BNPL\Ui\DataProvider;

use Hokodo\BNPL\Model\Logger\Fs\Collection;
use Hokodo\BNPL\Model\Logger\Fs\CollectionFactory;
use Magento\Ui\DataProvider\AbstractDataProvider;

class LogsDataProvider extends AbstractDataProvider
{
    /**
     * @var CollectionFactory
     */
    private CollectionFactory $collectionFactory;

    /**
     * @var Collection
     */
    protected $collection;

    /**
     * @param $name
     * @param $primaryFieldName
     * @param $requestFieldName
     * @param CollectionFactory $collectionFactory
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * Get Logs Collection.
     *
     * @return Collection
     * @throws \Exception
     */
    public function getCollection(): Collection
    {
        $regex = '/^.*\.(log)$/i';
        $this->collection = $this->collectionFactory->create();
        $this->collection->setCollectRecursively(true);
        $this->collection->setFilesFilter($regex);
        $this->collection->addTargetDir($this->collection->getLogDirectory()->getAbsolutePath());
        return $this->collection;
    }

    /**
     * Get Data Array.
     *
     * @return array
     * @throws \Exception
     */
    public function getData(): array
    {
        if (!$this->getCollection()->isLoaded()) {
            $this->getCollection()->load();
        }
        return $this->getCollection()->toArray();
    }
}
