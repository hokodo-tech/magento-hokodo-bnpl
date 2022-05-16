<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Gateway;

/**
 * Interface Hokodo\BNPL\Gateway\SubjectReaderInterface.
 */
interface SubjectReaderInterface
{
    /**
     * A function reads field value.
     *
     * @param string $fieldValue
     * @param array  $subject
     *
     * @return mixed
     */
    public function readFieldValue($fieldValue, array $subject);

    /**
     * A function reads endpoint param.
     *
     * @param string $param
     * @param array  $subject
     */
    public function readEndpointParam($param, array $subject);
}
