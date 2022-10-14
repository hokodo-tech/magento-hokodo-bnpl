<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Hokodo\BNPL\Gateway\Request\Sdk;

use Magento\Framework\ObjectManagerInterface;
use Magento\Payment\Gateway\Request\BuilderInterface;

/**
 * Class Hokodo\BNPL\Gateway\Request\GeneralSubjectBuilder.
 */
class GeneralSubjectBuilder implements BuilderInterface
{
    private $endpointBuilder;

    public function __construct(
        ObjectManagerInterface $objectManager,
        $endpointBuilder = null
    ) {
        $this->endpointBuilder = $endpointBuilder ? $objectManager->get($endpointBuilder['instance']) : null;
    }

    /**
     * @inheritDoc
     *
     * @see \Magento\Payment\Gateway\Request\BuilderInterface::build()
     */
    public function build(array $buildSubject)
    {
        if ($this->endpointBuilder) {
            $this->clearSubject($buildSubject);
        }
        return [
            'body' => $buildSubject,
        ];
    }

    /**
     * Clear subject from useless endpoint info.
     *
     * @param array $buildSubject
     *
     * @return void
     */
    private function clearSubject(array &$buildSubject): void
    {
        foreach ($this->endpointBuilder->getParams() as $value) {
            unset($buildSubject[$value]);
        }
    }
}
