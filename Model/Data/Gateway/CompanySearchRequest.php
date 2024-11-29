<?php

/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Hokodo\BNPL\Model\Data\Gateway;

use Hokodo\BNPL\Api\Data\Gateway\CompanySearchRequestInterface;
use Magento\Framework\Api\AbstractSimpleObject;

class CompanySearchRequest extends AbstractSimpleObject implements CompanySearchRequestInterface
{
    /**
     * @inheritdoc
     */
    public function setRegNumber(string $regNumber): self
    {
        $this->setData(self::REGISTRATION_NUMBER, $regNumber);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setCountry(string $country): self
    {
        $this->setData(self::COUNTRY, $country);
        return $this;
    }
}
