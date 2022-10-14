<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Hokodo\BNPL\Model\Data\Gateway;

use Hokodo\BNPL\Api\Data\Gateway\DeferredPaymentsPostSaleActionInterface;
use Magento\Framework\Api\AbstractSimpleObject;

class DeferredPaymentsPostSaleAction extends AbstractSimpleObject implements DeferredPaymentsPostSaleActionInterface
{
    /**
     * @inheritdoc
     */
    public function setPaymentId(string $paymentId): self
    {
        $this->setData(self::PAYMENT_ID, $paymentId);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setType(string $type): self
    {
        $this->setData(self::TYPE, $type);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setAmount(int $amount): self
    {
        $this->setData(self::AMOUNT, $amount);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getMetadata(): array
    {
        return $this->_get(self::METADATA);
    }

    /**
     * @inheritdoc
     */
    public function setMetadata(array $metadata): self
    {
        $this->setData(self::METADATA, $metadata);
        return $this;
    }
}
