<?php

namespace Hokodo\BNPL\Api\Data\Gateway;

interface CompanyCreditRequestInterface
{
    public const COMPANY_ID = 'company_id';
    public const CURRENCY = 'currency';

    public const CURRENCY_EUR = 'EUR';
    public const CURRENCY_GBP = 'GBP';

    /**
     * Currency setter.
     *
     * @param string $currency
     *
     * @return $this
     */
    public function setCurrency(string $currency): self;

    /**
     * Company Id setter.
     *
     * @param string $companyId
     *
     * @return $this
     */
    public function setCompanyId(string $companyId): self;
}
