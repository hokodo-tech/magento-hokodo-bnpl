<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Hokodo\BNPL\Model\Data;

use Hokodo\BNPL\Api\Data\HokodoCustomerInterface;
use Hokodo\BNPL\Model\ResourceModel\HokodoCustomer as ResourceModel;
use Magento\Framework\Model\AbstractModel;

class HokodoCustomer extends AbstractModel implements HokodoCustomerInterface
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'hokodo_customer';

    /**
     * Initialize magento model.
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(ResourceModel::class);
    }

    /**
     * @inheritdoc
     */
    public function getCustomerId(): ?int
    {
        $id = $this->getData(self::CUSTOMER_ID);
        return $id ? (int) $id : null;
    }

    /**
     * @inheritdoc
     */
    public function setCustomerId(int $customerId): self
    {
        $this->setData(self::CUSTOMER_ID, $customerId);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getCompanyId(): ?string
    {
        return $this->getData(self::COMPANY_ID);
    }

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
    public function getUserId(): ?string
    {
        return $this->getData(self::USER_ID);
    }

    /**
     * @inheritdoc
     */
    public function setUserId(string $userId): self
    {
        $this->setData(self::USER_ID, $userId);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getOrganisationId(): ?string
    {
        return $this->getData(self::ORGANISATION_ID);
    }

    /**
     * @inheritdoc
     */
    public function setOrganisationId(string $organisationId): self
    {
        $this->setData(self::ORGANISATION_ID, $organisationId);
        return $this;
    }
}
