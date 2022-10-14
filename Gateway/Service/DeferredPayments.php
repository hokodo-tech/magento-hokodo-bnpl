<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Hokodo\BNPL\Gateway\Service;

use Hokodo\BNPL\Api\Data\Gateway\DeferredPaymentsPostSaleActionInterface;
use Magento\Framework\Exception\NotFoundException;
use Magento\Payment\Gateway\Command\CommandException;
use Magento\Payment\Gateway\Command\ResultInterface;

class DeferredPayments extends AbstractService
{
    /**
     * @throws NotFoundException
     * @throws CommandException
     */
    public function capture(DeferredPaymentsPostSaleActionInterface $postSaleAction): ResultInterface
    {
        $postSaleAction->setType(DeferredPaymentsPostSaleActionInterface::TYPE_CAPTURE);
        return $this->commandPool->get('hokodo_post_sale')->execute($postSaleAction->__toArray());
    }
}
