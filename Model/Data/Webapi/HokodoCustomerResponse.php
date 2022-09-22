<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Hokodo\BNPL\Model\Data\Webapi;

use Hokodo\BNPL\Api\Data\Webapi\HokodoCustomerResponseInterface;
use Hokodo\BNPL\Api\Data\HokodoCustomerInterface;
use Magento\Framework\Api\AbstractSimpleObject;

class HokodoCustomerResponse extends AbstractSimpleObject implements HokodoCustomerResponseInterface
{
    /**
     * @inheritdoc
     */
    public function getOrganisationId(): string
    {
        return $this->_get(HokodoCustomerInterface::ORGANISATION_ID);
    }

    /**
     * @inheritdoc
     */
    public function setOrganisationId(string $organisationId): self
    {
        $this->setData(HokodoCustomerInterface::ORGANISATION_ID, $organisationId);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getUserId(): string
    {
        return $this->_get(HokodoCustomerInterface::USER_ID);
    }

    /**
     * @inheritdoc
     */
    public function setUserId(string $userId): self
    {
        $this->setData(HokodoCustomerInterface::USER_ID, $userId);
        return $this;
    }
}
