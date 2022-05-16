<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Gateway\Request;

use Hokodo\BNPL\Gateway\SubjectReaderInterface;
use Magento\Payment\Gateway\Request\BuilderInterface;

/**
 * Class Hokodo\BNPL\Gateway\Request\FieldValueBuilder.
 */
class FieldValueBuilder implements BuilderInterface
{
    /**
     * @var SubjectReaderInterface
     */
    private $subjectReader;

    /**
     * @var string
     */
    private $field;

    /**
     * @var string
     */
    private $value;

    /**
     * @param SubjectReaderInterface $subjectReader
     * @param string                 $field
     * @param string                 $value
     */
    public function __construct(
        SubjectReaderInterface $subjectReader,
        $field,
        $value
    ) {
        $this->field = $field;
        $this->value = $value;
        $this->subjectReader = $subjectReader;
    }

    /**
     * @inheritDoc
     *
     * @see \Magento\Payment\Gateway\Request\BuilderInterface::build()
     */
    public function build(array $buildSubject)
    {
        return [
            'body' => [
                $this->field => $this->subjectReader->readFieldValue($this->value, $buildSubject),
            ],
        ];
    }
}
