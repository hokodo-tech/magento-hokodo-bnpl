<?php

/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Hokodo\BNPL\Api\Data\Webapi;

interface HokodoCustomerResponseInterface
{
    /**
     * Organisation Id getter.
     *
     * @return string
     */
    public function getOrganisationId(): string;

    /**
     * Organisation Id setter.
     *
     * @param string $organisationId
     *
     * @return $this
     */
    public function setOrganisationId(string $organisationId): self;

    /**
     * User Id getter.
     *
     * @return string
     */
    public function getUserId(): string;

    /**
     * User Id setter.
     *
     * @param string $userId
     *
     * @return $this
     */
    public function setUserId(string $userId): self;
}
