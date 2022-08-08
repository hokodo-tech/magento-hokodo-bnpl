<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Hokodo\BNPL\Model\Data\Webapi;

use Hokodo\BNPL\Api\Data\Webapi\UserOrganisationInterface;
use Magento\Framework\Api\AbstractSimpleObject;

class UserOrganisation extends AbstractSimpleObject implements UserOrganisationInterface
{
    /**
     * @inheritdoc
     */
    public function setId(string $id): self
    {
        $this->setData(self::ID, $id);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setRole(string $role): self
    {
        $this->setData(self::ROLE, $role);
        return $this;
    }
}
