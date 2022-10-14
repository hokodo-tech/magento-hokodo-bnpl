<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Hokodo\BNPL\Api\Data;

interface PostSaleEventInterface
{
    public const CREATED = 'created';
    public const TYPE = 'type';
    public const AMOUNT = 'amount';
    public const METADATA = 'metadata';
    public const CHANGES = 'changes';

    /**
     * Created getter.
     *
     * @return string
     */
    public function getCreated(): string;

    /**
     * Created setter.
     *
     * @param string $created
     *
     * @return $this
     */
    public function setCreated(string $created): self;

    /**
     * Type getter.
     *
     * @return string
     */
    public function getType(): string;

    /**
     * Type setter.
     *
     * @param string $type
     *
     * @return $this
     */
    public function setType(string $type): self;

    /**
     * Amount getter.
     *
     * @return int
     */
    public function getAmount(): int;

    /**
     * Amount setter.
     *
     * @param int $amount
     *
     * @return $this
     */
    public function setAmount(int $amount): self;

    /**
     * Changes getter.
     *
     * @return PostSaleEventChangesInterface
     */
    public function getChanges(): PostSaleEventChangesInterface;

    /**
     * Changes setter.
     *
     * @param PostSaleEventChangesInterface $changes
     *
     * @return $this
     */
    public function setChanges(PostSaleEventChangesInterface $changes): self;

    /**
     * Metadata getter.
     *
     * @return string[]
     */
    public function getMetadata(): array;

    /**
     * Metadata setter.
     *
     * @param string[] $metadata
     *
     * @return $this
     */
    public function setMetadata(array $metadata): self;
}
