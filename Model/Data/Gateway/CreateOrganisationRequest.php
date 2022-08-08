<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Hokodo\BNPL\Model\Data\Gateway;

use Hokodo\BNPL\Api\Data\Gateway\CreateOrganisationRequestInterface;
use Magento\Framework\Api\AbstractSimpleObject;

class CreateOrganisationRequest extends AbstractSimpleObject implements CreateOrganisationRequestInterface
{
    /**
     * @inheritdoc
     */
    public function setCompanyId(string $companyId): self
    {
        $this->setData(self::COMPANY_ID, $companyId);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setUniqueId(string $uniqueId): self
    {
        $this->setData(self::UNIQUE_ID, $uniqueId);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setRegistered(string $registered): self
    {
        $this->setData(self::REGISTERED, $registered);
        return $this;
    }
}
