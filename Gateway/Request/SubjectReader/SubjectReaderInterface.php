<?php

/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Hokodo\BNPL\Gateway\Request\SubjectReader;

interface SubjectReaderInterface
{
    /**
     * Read param in provided subject.
     *
     * @param array $subject
     *
     * @return string
     */
    public function readSubjectParam(array $subject): string;
}
