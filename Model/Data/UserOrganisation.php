<?php
/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Model\Data;

use Hokodo\BNPL\Api\Data\UserOrganisationInterface;
use Magento\Framework\Api\AbstractSimpleObject;

/**
 * Class Hokodo\BNPL\Model\Data\UserOrganisation.
 */
class UserOrganisation extends AbstractSimpleObject implements UserOrganisationInterface
{
    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\UserOrganisationInterface::setId()
     */
    public function setId($id)
    {
        return $this->setData(self::ID, $id);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\UserOrganisationInterface::getId()
     */
    public function getId()
    {
        return $this->_get(self::ID);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\UserOrganisationInterface::setRole()
     */
    public function setRole($role)
    {
        return $this->setData(self::ROLE, $role);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\UserOrganisationInterface::getRole()
     */
    public function getRole()
    {
        return $this->_get(self::ROLE);
    }
}
