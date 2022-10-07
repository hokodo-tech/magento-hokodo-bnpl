<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Hokodo\BNPL\Service;

use Hokodo\BNPL\Gateway\Config\Config;
use Magento\Customer\Model\Session;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

class CustomersGroup
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
     * @param Config  $config
     * @param Session $customerSession
     */
    public function __construct(
        Config $config,
        Session $customerSession
    ) {
        $this->config = $config;
        $this->customerSession = $customerSession;
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
        return in_array($this->customerSession->getCustomerGroupId(), $this->config->getCustomerGroups(), true);
    }
}
