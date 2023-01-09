<?php
/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
// @codingStandardsIgnoreFile

declare(strict_types=1);

namespace Hokodo\BNPL\Api\Data\Webapi;

interface CreateUserResponseInterface
{
    public const USER_ID = 'id';

    /**
     * @return string
     */
    public function getId(): string;

    /**
     * @param string $id
     *
     * @return $this
     */
    public function setId(string $id): self;
}
