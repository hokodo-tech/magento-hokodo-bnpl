<?php
/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Gateway\Http\Converter;

use InvalidArgumentException;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Payment\Gateway\Http\ConverterException;
use Magento\Payment\Gateway\Http\ConverterInterface;

/**
 * Class Hokodo\BNPL\Gateway\Http\Converter\JsonToArrayConverter.
 */
class JsonToArrayConverter implements ConverterInterface
{
    /**
     * @var Json
     */
    private $json;

    /**
     * @param Json $json
     */
    public function __construct(
        Json $json
    ) {
        $this->json = $json;
    }

    /**
     * @inheritDoc
     *
     * @see \Magento\Payment\Gateway\Http\ConverterInterface::convert()
     */
    public function convert($response)
    {
        if (!is_string($response)) {
            throw new ConverterException(__('The response type is incorrect. Verify the type and try again.'));
        }

        if (!$response) {
            return [];
        }

        try {
            return $this->json->unserialize($response);
        } catch (InvalidArgumentException $e) {
            throw new ConverterException(__($e->getMessage()));
        }
    }
}
