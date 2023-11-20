<?php
/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Hokodo\BNPL\Gateway\Request;

use Hokodo\BNPL\Gateway\Request\SubjectReader\SubjectReaderInterface;
use Magento\Payment\Gateway\Request\BuilderInterface;

/**
 * Class Hokodo\BNPL\Gateway\Request\EndpointBuilder.
 */
class EndpointBuilder implements BuilderInterface
{
    /**
     * @var string
     */
    private $endpoint;

    /**
     * @var array
     */
    private $params;

    /**
     * @param string $endpoint
     * @param array  $params
     */
    public function __construct(
        string $endpoint,
        array $params = []
    ) {
        $this->endpoint = $endpoint;
        $this->params = $params;
    }

    /**
     * @inheritDoc
     *
     * @see \Magento\Payment\Gateway\Request\BuilderInterface::build()
     */
    public function build(array $buildSubject)
    {
        return [
            'uri' => $this->buildUri($buildSubject),
        ];
    }

    /**
     * A function that build uri.
     *
     * @param array $buildSubject
     *
     * @return string
     */
    private function buildUri(array $buildSubject): string
    {
        $params = [];
        foreach ($this->params as $key => $value) {
            if ($value instanceof SubjectReaderInterface) {
                $params[$key] = $value->readSubjectParam($buildSubject);
            } else {
                $params[$key] = $buildSubject[$value];
                unset($buildSubject[$value]);
            }
        }

        return str_replace(array_keys($params), array_values($params), $this->endpoint);
    }

    /**
     * Get request params.
     *
     * @return array
     */
    public function getParams(): array
    {
        return $this->params;
    }
}
