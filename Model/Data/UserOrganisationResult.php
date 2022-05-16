<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Model\Data;

use Hokodo\BNPL\Api\Data\UserOrganisationResultInterface;
use Magento\Framework\Api\AbstractSimpleObject;

/**
 * Class Hokodo\BNPL\Model\Data\UserOrganisationResult.
 */
class UserOrganisationResult extends AbstractSimpleObject implements UserOrganisationResultInterface
{
    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\UserOrganisationResultInterface::setUser()
     */
    public function setUser(\Hokodo\BNPL\Api\Data\UserInterface $user)
    {
        return $this->setData(self::USER, $user);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\UserOrganisationResultInterface::getUser()
     */
    public function getUser()
    {
        return $this->_get(self::USER);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\UserOrganisationResultInterface::setOrganisation()
     */
    public function setOrganisation(\Hokodo\BNPL\Api\Data\HokodoOrganisationInterface $organisation)
    {
        return $this->setData(self::ORGANISATION, $organisation);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\UserOrganisationResultInterface::getOrganisation()
     */
    public function getOrganisation()
    {
        return $this->_get(self::ORGANISATION);
    }
}
