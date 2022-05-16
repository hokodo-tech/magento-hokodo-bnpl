<?php

declare(strict_types=1);
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Ui\Component\Listing\Column;

use Magento\Framework\Serialize\JsonValidator;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;

/**
 * Class PaymentLogContent Generate content for payment logs.
 */
class PaymentLogContent extends Column
{
    /**
     * ContextInterface.
     *
     * @var ContextInterface
     */
    protected $context;

    /**
     * UiComponentFactory User Interface Component Factory.
     *
     * @var UiComponentFactory
     */
    protected $uiComponentFactory;

    /**
     * Json class.
     *
     * @var Json
     */
    protected $json;

    /**
     * Json validator class.
     *
     * @var JsonValidator
     */
    protected $jsonValidator;

    /**
     * @var array
     */
    protected $components;

    /**
     * @var array
     */
    protected $data;

    /**
     * Action constructor.
     *
     * @param ContextInterface   $context
     * @param UiComponentFactory $uiComponentFactory
     * @param Json               $json
     * @param JsonValidator      $jsonValidator
     * @param array              $components
     * @param array              $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        Json $json,
        JsonValidator $jsonValidator,
        array $components = [],
        array $data = []
    ) {
        $this->json = $json;
        $this->jsonValidator = $jsonValidator;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Prepare Data Source.
     *
     * @param array $dataSource
     *
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            $fieldName = $this->getName();
            foreach ($dataSource['data']['items'] as &$item) {
                $content = $item[$fieldName];
                if (isset($item[$fieldName])
                    && $this->jsonValidator->isValid($item[$fieldName])
                ) {
                    // change content to html table
                    $jsonData = $this->json->unserialize($item[$fieldName]);
                    $content = '<table>';
                    foreach ($jsonData as $key => $value) {
                        /** @var mixed $value */
                        if (is_object($value) || is_array($value)) {
                            $content .= "<tr><td style='border: unset;'>"
                                . print_r($value, true) . '</td></tr>'; // @codingStandardsIgnoreLine
                        } else {
                            $content .= "<tr><td style='border: unset;'>" . $value . '</td></tr>';
                        }
                    }
                    $content .= '</table>';
                    $content = html_entity_decode($content); // @codingStandardsIgnoreLine
                }
                $item[$fieldName] = $content;
            }
        }

        return $dataSource;
    }
}
