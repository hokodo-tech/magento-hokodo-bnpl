<?php
/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Hokodo\BNPL\Service;

use Hokodo\BNPL\Gateway\Config\Config;
use Hokodo\BNPL\Model\HokodoCompanyProvider;
use Magento\Customer\Model\Session;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

class Customer
{
    /**
     * @var Config
     */
    private Config $config;

    /**
     * @var Session
     */
    private Session $customerSession;

    /**
     * @var \Hokodo\BNPL\Model\HokodoCompanyProvider
     */
    private HokodoCompanyProvider $hokodoCompanyProvider;

    /**
     * @param Config                                   $config
     * @param Session                                  $customerSession
     * @param \Hokodo\BNPL\Model\HokodoCompanyProvider $hokodoCompanyProvider
     */
    public function __construct(
        Config $config,
        Session $customerSession,
        HokodoCompanyProvider $hokodoCompanyProvider
    ) {
        $this->config = $config;
        $this->customerSession = $customerSession;
        $this->hokodoCompanyProvider = $hokodoCompanyProvider;
    }

    /**
     * Check is module enabled for customer.
     *
     * @return bool
     */
    public function isEnabledForCustomerGroup(): bool
    {
        try {
            if ($this->config->isCustomerGroupsEnabled()) {
                return $this->isUserInCustomerGroups();
            }
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Check if user in selected customers groups.
     *
     * @throws NoSuchEntityException
     * @throws LocalizedException
     */
    private function isUserInCustomerGroups(): bool
    {
        return in_array(
            (string) $this->customerSession->getCustomerGroupId(),
            $this->config->getCustomerGroups(),
            true
        );
    }

    /**
     * Get credit limit available amount for customer.
     *
     * @return int|null
     */
    public function getCustomerAmountAvailable(): ?int
    {
        $amountAvailable = null;
        if ($customerId = $this->customerSession->getCustomerId()) {
            $hokodoEntity = $this->hokodoCompanyProvider->getEntityRepository()->getByCustomerId((int) $customerId);
            if ($creditLimit = $hokodoEntity->getCreditLimit()) {
                $amountAvailable = $creditLimit->getAmountAvailable() / 100;
            }
        }

        return $amountAvailable;
    }
}
