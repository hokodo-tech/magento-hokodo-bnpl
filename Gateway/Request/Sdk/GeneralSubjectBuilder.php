<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Hokodo\BNPL\Gateway\Request\Sdk;

use Magento\Payment\Gateway\Request\BuilderInterface;

/**
 * Class Hokodo\BNPL\Gateway\Request\GeneralSubjectBuilder.
 */
class GeneralSubjectBuilder implements BuilderInterface
{
    /**
     * @inheritDoc
     *
     * @see \Magento\Payment\Gateway\Request\BuilderInterface::build()
     */
    public function build(array $buildSubject)
    {
        return [
            'body' => $buildSubject,
        ];
    }
}
