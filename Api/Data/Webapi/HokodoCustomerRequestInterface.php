<?php

/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Hokodo\BNPL\Api\Data\Webapi;

interface HokodoCustomerRequestInterface
{
    /**
     * Company id getter.
     *
     * @return string
     */
    public function getCompanyId(): string;

    /**
     * Company id setter.
     *
     * @param string $companyId
     *
     * @return $this
     */
    public function setCompanyId(string $companyId): self;

    /**
     * Customer Id getter.
     *
     * @return int|null
     */
    public function getCustomerId(): ?int;

    /**
     * Customer Id setter.
     *
     * @param int|null $customerId
     *
     * @return $this
     */
    public function setCustomerId(?int $customerId): self;
}
