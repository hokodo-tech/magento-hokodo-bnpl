<?php
/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Hokodo\BNPL\Model\Data;

use Hokodo\BNPL\Api\Data\PostSaleEventChangesInterface;
use Hokodo\BNPL\Api\Data\PostSaleEventInterface;
use Magento\Framework\DataObject;

class PostSaleEvent extends DataObject implements PostSaleEventInterface
{
    /**
     * @inheritdoc
     */
    public function getCreated(): string
    {
        return $this->getData(self::CREATED);
    }

    /**
     * @inheritdoc
     */
    public function setCreated(string $created): self
    {
        $this->setData(self::CREATED, $created);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getType(): string
    {
        return $this->getData(self::TYPE);
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
    public function getAmount(): int
    {
        return $this->getData(self::AMOUNT);
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
        return $this->getData(self::METADATA);
    }

    /**
     * @inheritdoc
     */
    public function setMetadata(array $metadata): self
    {
        $this->setData(self::METADATA, $metadata);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getChanges(): PostSaleEventChangesInterface
    {
        return $this->getData(self::CHANGES);
    }

    /**
     * @inheritdoc
     */
    public function setChanges(PostSaleEventChangesInterface $changes): self
    {
        $this->setData(self::CHANGES, $changes);
        return $this;
    }
}
