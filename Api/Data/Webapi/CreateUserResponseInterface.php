<?php

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
