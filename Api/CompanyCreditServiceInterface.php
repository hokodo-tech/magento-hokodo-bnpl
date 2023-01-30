<?php

namespace Hokodo\BNPL\Api;

use Hokodo\BNPL\Api\Data\Company\CreditInterface;
use Hokodo\BNPL\Api\Data\Company\CreditLimitInterface;

interface CompanyCreditServiceInterface
{
    /**
     * Company Credit getter.
     *
     * @param string $companyId
     *
     * @return CreditInterface|null
     */
    public function getCredit(string $companyId): ?CreditInterface;

    /**
     * Get Hokodo company Credit limit by Hokodo company Id.
     *
     * @param string $companyId
     *
     * @return CreditLimitInterface|null
     */
    public function getCreditLimit(string $companyId): ?CreditLimitInterface;
}
