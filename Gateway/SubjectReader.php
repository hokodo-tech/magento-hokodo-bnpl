<?php
/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Gateway;

/**
 * Class Hokodo\BNPL\Gateway\SubjectReader.
 */
class SubjectReader implements SubjectReaderInterface
{
    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Gateway\SubjectReaderInterface::readFieldValue()
     */
    public function readFieldValue($fieldValue, array $subject)
    {
        if (!isset($subject[$fieldValue])) {
            throw new \InvalidArgumentException(sprintf('Missing field %s', $fieldValue));
        }

        return $subject[$fieldValue];
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Gateway\SubjectReaderInterface::readEndpointParam()
     */
    public function readEndpointParam($param, array $subject)
    {
        return $this->readFieldValue($param, $subject);
    }
}
