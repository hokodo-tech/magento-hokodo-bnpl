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

    /**
     * @throws NotFoundException
     * @throws CommandException
     */
    public function captureRemaining(DeferredPaymentsPostSaleActionInterface $postSaleAction): ResultInterface
    {
        $postSaleAction->setType(DeferredPaymentsPostSaleActionInterface::TYPE_CAPTURE_REMAINING);
        return $this->commandPool->get('hokodo_post_sale')->execute($postSaleAction->__toArray());
    }

    /**
     * @throws NotFoundException
     * @throws CommandException
     */
    public function refund(DeferredPaymentsPostSaleActionInterface $postSaleAction): ResultInterface
    {
        $postSaleAction->setType(DeferredPaymentsPostSaleActionInterface::TYPE_REFUND);
        return $this->commandPool->get('hokodo_post_sale')->execute($postSaleAction->__toArray());
    }

    /**
     * @throws NotFoundException
     * @throws CommandException
     */
    public function void(DeferredPaymentsPostSaleActionInterface $postSaleAction): ResultInterface
    {
        $postSaleAction->setType(DeferredPaymentsPostSaleActionInterface::TYPE_VOID);
        return $this->commandPool->get('hokodo_post_sale')->execute($postSaleAction->__toArray());
    }

    /**
     * @throws NotFoundException
     * @throws CommandException
     */
    public function voidRemaining(DeferredPaymentsPostSaleActionInterface $postSaleAction): ResultInterface
    {
        $postSaleAction->setType(DeferredPaymentsPostSaleActionInterface::TYPE_VOID_REMAINING);
        return $this->commandPool->get('hokodo_post_sale')->execute($postSaleAction->__toArray());
    }
}
