<?php

/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Ui\Component\Listing\Column\Logs;

use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;

class Actions extends Column
{
    public const URL_PATH_LOG_DOWNLOAD = 'hokodo/logs/download';
    public const URL_PATH_LOG_DELETE = 'hokodo/logs/delete';

    /**
     * @var UrlInterface
     */
    public UrlInterface $urlBuilder;

    /**
     * @param UrlInterface       $urlBuilder
     * @param ContextInterface   $context
     * @param UiComponentFactory $uiComponentFactory
     * @param array              $components
     * @param array              $data
     */
    public function __construct(
        UrlInterface $urlBuilder,
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        array $components = [],
        array $data = []
    ) {
        $this->urlBuilder = $urlBuilder;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Prepare Data Source.
     *
     * @param array $dataSource
     *
     * @return array
     */
    public function prepareDataSource(array $dataSource): array
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                if (isset($item['file_name'])) {
                    $item[$this->getData('name')] = [
                        'download' => [
                            'href' => $this->urlBuilder->getUrl(
                                static::URL_PATH_LOG_DOWNLOAD,
                                [
                                    'file_name' => $item['file_name'],
                                    'size' => $item['size'],
                                ]
                            ),
                            'label' => __('Download'),
                        ],
                        'delete' => [
                            'href' => $this->urlBuilder->getUrl(
                                self::URL_PATH_LOG_DELETE,
                                [
                                    'file_name' => $item['file_name'],
                                ]
                            ),
                            'label' => __('Delete'),
                            'confirm' => [
                                'title' => __('Delete %1', $item['file_name']),
                                'message' => __(
                                    'Are you sure you want to delete a %1 file?',
                                    $item['file_name']
                                ),
                            ],
                            'post' => true,
                        ],
                    ];
                }
            }
        }

        return $dataSource;
    }
}
