<?php
/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Hokodo\BNPL\Api\Data;

interface HokodoEntityInterface
{
    /**
     * Company Id getter.
     *
     * @return string|null
     */
    public function getCompanyId(): ?string;

    /**
     * Company Id setter.
     *
     * @param string|null $companyId
     *
     * @return $this
     */
    public function setCompanyId(?string $companyId): self;
}
