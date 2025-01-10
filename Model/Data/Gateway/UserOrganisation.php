<?php

/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Hokodo\BNPL\Model\Data\Gateway;

use Hokodo\BNPL\Api\Data\Gateway\UserOrganisationInterface;
use Magento\Framework\Api\AbstractSimpleObject;

class UserOrganisation extends AbstractSimpleObject implements UserOrganisationInterface
{
    /**
     * @inheritDoc
     */
    public function setId(string $id): self
    {
        $this->setData(self::ID, $id);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setRole(string $role): self
    {
        $this->setData(self::ROLE, $role);
        return $this;
    }
}
