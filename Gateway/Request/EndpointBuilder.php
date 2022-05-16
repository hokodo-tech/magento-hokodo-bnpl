<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Gateway\Request;

use Hokodo\BNPL\Gateway\SubjectReaderInterface;
use Magento\Payment\Gateway\Request\BuilderInterface;

/**
 * Class Hokodo\BNPL\Gateway\Request\EndpointBuilder.
 */
class EndpointBuilder implements BuilderInterface
{
    /**
     * @var SubjectReaderInterface
     */
    private $subjectReader;

    /**
     * @var string
     */
    private $endpoint;

    /**
     * @var array
     */
    private $params;

    /**
     * @param SubjectReaderInterface $subjectReader
     * @param string                 $endpoint
     * @param array                  $params
     */
    public function __construct(
        SubjectReaderInterface $subjectReader,
        $endpoint,
        array $params = []
    ) {
        $this->subjectReader = $subjectReader;
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
    private function buildUri(array $buildSubject)
    {
        $params = [];
        foreach ($this->params as $key => $param) {
            $params[$key] = $this->buildParam($param, $buildSubject);
        }

        return str_replace(array_keys($params), array_values($params), $this->endpoint);
    }

    /**
     * A function that build param.
     *
     * @param string $param
     * @param array  $buildSubject
     *
     * @return string
     */
    private function buildParam($param, array $buildSubject)
    {
        return $this->subjectReader->readEndpointParam($param, $buildSubject);
    }
}
