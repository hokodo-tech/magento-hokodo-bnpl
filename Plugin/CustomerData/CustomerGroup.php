<?php
/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Hokodo\BNPL\Plugin\CustomerData;

use Hokodo\BNPL\Gateway\Config\Config;
use Magento\Customer\Api\GroupManagementInterface;
use Magento\Customer\CustomerData\Customer;
use Magento\Customer\Model\Session;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

class CustomerGroup
{
    /**
     * @var Session
     */
    private Session $customerSession;

    /**
     * @var GroupManagementInterface
     */
    private GroupManagementInterface $groupManagement;

    /**
     * @var Config
     */
    private Config $config;

    /**
     * @param Session                  $customerSession
     * @param GroupManagementInterface $groupManagement
     * @param Config                   $config
     */
    public function __construct(
        Session $customerSession,
        GroupManagementInterface $groupManagement,
        Config $config
    ) {
        $this->customerSession = $customerSession;
        $this->groupManagement = $groupManagement;
        $this->config = $config;
    }

    /**
     * Add Customer's group id for frontend banners.
     *
     * @param Customer $subject
     * @param array    $result
     *
     * @return mixed
     *
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function afterGetSectionData(Customer $subject, array $result)
    {
        $canPushAnalytics = true;
        $customer = $this->customerSession->getCustomer();
        if ($customer && $this->customerSession->isLoggedIn()) {
            $result['hokodoCustomerGroup'] = $customer->getGroupId();
        } else {
            $result['hokodoCustomerGroup'] = $this->groupManagement->getNotLoggedInGroup()->getId();
        }

        if ($this->config->isCustomerGroupsEnabled()
            && !in_array($result['hokodoCustomerGroup'], $this->config->getCustomerGroups())) {
            $canPushAnalytics = false;
        }

        $result['canPushAnalytics'] = $canPushAnalytics;
        return $result;
    }
}
