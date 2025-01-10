<?php

/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Hokodo\BNPL\Model\Data\Gateway;

use Hokodo\BNPL\Api\Data\Gateway\CreateOrderRequestInterface;
use Hokodo\BNPL\Api\Data\Gateway\OrderCustomerInterface;
use Magento\Framework\Api\AbstractSimpleObject;

class CreateOrderRequest extends AbstractSimpleObject implements CreateOrderRequestInterface
{
    /**
     * @inheritdoc
     */
    public function setUniqueId(string $uniqueId): self
    {
        $this->setData(self::UNIQUE_ID, $uniqueId);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setCustomer(OrderCustomerInterface $customer): self
    {
        $this->setData(self::CUSTOMER, $customer);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setStatus(string $status): self
    {
        $this->setData(self::STATUS, $status);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setCurrency(string $currency): self
    {
        $this->setData(self::CURRENCY, $currency);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setTotalAmount(int $totalAmount): self
    {
        $this->setData(self::TOTAL_AMOUNT, $totalAmount);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setTaxAmount(int $taxAmount): self
    {
        $this->setData(self::TAX_AMOUNT, $taxAmount);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setOrderDate(string $orderDate): self
    {
        $this->setData(self::ORDER_DATE, $orderDate);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setItems(array $items): self
    {
        $this->setData(self::ITEMS, $items);
        return $this;
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
