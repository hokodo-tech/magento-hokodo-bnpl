<?php
/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Service;

use Magento\Payment\Gateway\Command\CommandPoolInterface;

/**
 * Class Hokodo\BNPL\Service\AbstractService.
 */
abstract class AbstractService
{
    /**
     * @var CommandPoolInterface
     */
    protected $commandPool;

    /**
     * @param CommandPoolInterface $commandPool
     */
    public function __construct(
        CommandPoolInterface $commandPool
    ) {
        $this->commandPool = $commandPool;
    }

    /**
     * A function that returns command code.
     *
     * @param string $commandCode
     * @param array  $commandSubject
     *
     * @return \Hokodo\BNPL\Gateway\Command\Result\Result
     */
    protected function executeCommand($commandCode, array $commandSubject)
    {
        return $this->commandPool->get($commandCode)->execute($commandSubject);
    }
}
