<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Model\Data;

use Hokodo\BNPL\Api\Data\OrganisationInterface;
use Magento\Framework\Api\AbstractSimpleObject;

/**
 * Class Hokodo\BNPL\Model\Data\Organisation.
 */
class Organisation extends AbstractSimpleObject implements OrganisationInterface
{
    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrganisationInterface::setId()
     */
    public function setId($id)
    {
        return $this->setData(self::ID, $id);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrganisationInterface::getId()
     */
    public function getId()
    {
        return $this->_get(self::ID);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrganisationInterface::setUniqueId()
     */
    public function setUniqueId($uniqueId)
    {
        return $this->setData(self::UNIQUE_ID, $uniqueId);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrganisationInterface::getUniqueId()
     */
    public function getUniqueId()
    {
        return $this->_get(self::UNIQUE_ID);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrganisationInterface::setRegistered()
     */
    public function setRegistered($registered)
    {
        return $this->setData(self::REGISTERED, $registered);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrganisationInterface::getRegistered()
     */
    public function getRegistered()
    {
        return $this->_get(self::REGISTERED);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrganisationInterface::setCompany()
     */
    public function setCompany($company)
    {
        return $this->setData(self::COMPANY, $company);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrganisationInterface::getCompany()
     */
    public function getCompany()
    {
        return $this->_get(self::COMPANY);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrganisationInterface::setUsers()
     */
    public function setUsers(array $users)
    {
        return $this->setData(self::USERS, $users);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrganisationInterface::getUsers()
     */
    public function getUsers()
    {
        return $this->_get(self::USERS);
    }
}
