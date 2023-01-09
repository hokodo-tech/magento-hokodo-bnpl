<?php
/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Hokodo\BNPL\Model\Data\Webapi;

use Hokodo\BNPL\Api\Data\HokodoCustomerInterface;
use Hokodo\BNPL\Api\Data\Webapi\HokodoCustomerRequestInterface;
use Magento\Framework\DataObject;

class HokodoCustomerRequest extends DataObject implements HokodoCustomerRequestInterface
{
    /**
     * @inheritdoc
     */
    public function getCompanyId(): string
    {
        return $this->getData(HokodoCustomerInterface::COMPANY_ID);
    }

    /**
     * @inheritdoc
     */
    public function setCompanyId(string $companyId): self
    {
        $this->setData(HokodoCustomerInterface::COMPANY_ID, $companyId);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getCustomerId(): ?int
    {
        return $this->getData(HokodoCustomerInterface::CUSTOMER_ID);
    }

    /**
     * @inheritdoc
     */
    public function setCustomerId(?int $customerId): self
    {
        $this->setData(HokodoCustomerInterface::CUSTOMER_ID, $customerId);
        return $this;
    }
}
