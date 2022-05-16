<?php

declare(strict_types=1);
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Model\PaymentLog;

use Hokodo\BNPL\Model\ResourceModel\PaymentLog\Collection;
use Hokodo\BNPL\Model\ResourceModel\PaymentLog\CollectionFactory;
use Magento\Framework\App\RequestInterface;
use Magento\Ui\DataProvider\AbstractDataProvider;

class DataProvider extends AbstractDataProvider
{
    /**
     * RequestInterface.
     *
     * @var RequestInterface
     */
    protected $request;

    /**
     * Link404 Collection.
     *
     * @var Collection
     */
    protected $collection;

    /**
     * LoadedData variable.
     *
     * @var array
     */
    protected $loadedData;

    /**
     * DataProvider constructor.
     *
     * @param string            $name
     * @param string            $primaryFieldName
     * @param string            $requestFieldName
     * @param CollectionFactory $link404CollectionFactory
     * @param RequestInterface  $request
     * @param array             $meta
     * @param array             $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $link404CollectionFactory,
        RequestInterface $request,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $link404CollectionFactory->create();
        $this->request = $request;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    /**
     * Returns items data.
     *
     * @return array
     */
    public function getData(): array
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }

        $items = $this->collection->getItems();
        if (!empty($items)) {
            foreach ($items as $link404) {
                $this->loadedData[$link404->getId()] = $link404->getData();
            }
            return $this->loadedData;
        }

        return [];
    }
}
