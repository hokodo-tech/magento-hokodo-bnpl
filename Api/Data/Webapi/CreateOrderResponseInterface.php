<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
// @codingStandardsIgnoreFile

declare(strict_types=1);

namespace Hokodo\BNPL\Api\Data\Webapi;

interface CreateOrderResponseInterface
{
    public const ORDER_ID = 'id';

    /**
     * Id getter.
     *
     * @return string
     */
    public function getId(): string;

    /**
     * Id setter.
     *
     * @param string $id
     *
     * @return $this
     */
    public function setId(string $id): self;
}
