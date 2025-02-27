<?php

/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
// @codingStandardsIgnoreFile

declare(strict_types=1);

namespace Hokodo\BNPL\Api\Data;

use Hokodo\BNPL\Api\Data\Company\CreditLimitInterface;

interface HokodoEntityInterface extends \Magento\Framework\Api\ExtensibleDataInterface
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

    /**
     * CreditLimit getter.
     *
     * @return \Hokodo\BNPL\Api\Data\Company\CreditLimitInterface|null
     */
    public function getCreditLimit(): ?CreditLimitInterface;

    /**
     * CreditLimit setter.
     *
     * @param \Hokodo\BNPL\Api\Data\Company\CreditLimitInterface|null $credit
     *
     * @return $this
     */
    public function setCreditLimit(?CreditLimitInterface $credit): self;
}
