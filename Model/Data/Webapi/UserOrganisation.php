<?php

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
