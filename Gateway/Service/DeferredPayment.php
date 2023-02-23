<?php
/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Hokodo\BNPL\Gateway\Service;

use Magento\Framework\Exception\NotFoundException;
use Magento\Payment\Gateway\Command\CommandException;
use Magento\Payment\Gateway\Command\ResultInterface;

class DeferredPayment extends AbstractService
{
    /**
     * API search payment gateway command.
     *
     * @param array $request
     *
     * @return ResultInterface
     *
     * @throws NotFoundException
     * @throws CommandException
     */
    public function getDeferredPayment(array $request): ResultInterface
    {
        return $this->commandPool->get('deferred_payment_info')->execute($request);
    }
}
