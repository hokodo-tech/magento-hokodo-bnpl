<?php

/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Hokodo\BNPL\Gateway\Service;

use Hokodo\BNPL\Gateway\Command\Result\DeferredPaymentResultInterface;
use Magento\Framework\Exception\NotFoundException;
use Magento\Payment\Gateway\Command\CommandException;

class DeferredPayment extends AbstractService
{
    /**
     * API search payment gateway command.
     *
     * @param array $request
     *
     * @return DeferredPaymentResultInterface
     *
     * @throws NotFoundException
     * @throws CommandException
     */
    public function getDeferredPayment(array $request)
    {
        return $this->commandPool->get('deferred_payment_info')->execute($request);
    }
}
