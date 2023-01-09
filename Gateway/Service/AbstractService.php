<?php
/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Gateway\Service;

use Magento\Framework\Exception\NotFoundException;
use Magento\Payment\Gateway\Command\CommandException;
use Magento\Payment\Gateway\Command\CommandPoolInterface;
use Magento\Payment\Gateway\Command\ResultInterface;

/**
 * Class Hokodo\BNPL\Gateway\Service\AbstractService.
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
     * @param object $commandSubject
     *
     * @return ResultInterface|null
     *
     * @throws NotFoundException
     * @throws CommandException
     */
    public function executeCommand(string $commandCode, object $commandSubject): ?ResultInterface
    {
        return $this->commandPool->get($commandCode)->execute($commandSubject->__toArray());
    }
}
