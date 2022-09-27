<?php
/**
 * Copyright © 2018-2022 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Hokodo\BNPL\ViewModel;

use Hokodo\BNPL\Gateway\Config\Config;
use Magento\Customer\Model\Session;
use Magento\Framework\View\Element\Block\ArgumentInterface;

class ProductButton implements ArgumentInterface
{
    /**
     * @var Config
     */
    private $config;

    /**
     * @var Session
     */
    private $customerSession;

    /**
     * ProductButton constructor.
     *
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
     * Get label for button from payment method config.
     *
     * @return string
     */
    public function getLabelBtn(): string
    {
        return $this->config->getValue(Config::KEY_BTN_PRODUCT_PAGE_LABEL);
    }

    /**
     * Get status display for button from payment method config.
     *
     * @return bool
     */
    public function isEnable(): bool
    {
        if ($this->config->getValue(Config::KEY_BTN_PRODUCT_PAGE_ENABLE) == '1') {
            return true;
        }

        return false;
    }

    /**
     * Get Api Key.
     *
     * @return string
     */
    public function getApiKey(): string
    {
        $apiKey = null;
        if ($this->isSandbox()) {
            $apiKey = $this->config->getValue(Config::KEY_SANDBOX_API_KEY);
        } else {
            $apiKey = $this->config->getValue(Config::KEY_API_KEY);
        }

        return $apiKey;
    }

    /**
     * Check if is sandbox.
     *
     * @return bool
     */
    public function isSandbox(): bool
    {
        if ($this->config->getValue(Config::KEY_ENVIRONMENT) == Config::ENV_SANDBOX) {
            return true;
        }

        return false;
    }

    /**
     * Check enable feature customer groups.
     *
     * @return bool
     */
    private function enableCustomerGroups(): bool
    {
        if ($this->config->getValue(Config::ENABLE_CUSTOMER_GROUPS) == '1') {
            return true;
        }

        return false;
    }

    /**
     * Get list customer group selected config.
     *
     * @return array
     */
    private function getSelectedCustomerGroups(): array
    {
        if ($this->config->getValue(Config::CUSTOMER_GROUPS)) {
            return explode(',', $this->config->getValue(Config::CUSTOMER_GROUPS));
        }
        return [];
    }

    /**
     * Check current customer group.
     *
     * @return int
     */
    private function getGroupId(): int
    {
        return (int) $this->customerSession->getCustomer()->getGroupId();
    }

    /**
     * Check current customer group possible to show banner.
     *
     * @return bool
     */
    private function checkGroupId(): bool
    {
        if (!$this->enableCustomerGroups()) {
            return true;
        }

        if (in_array($this->getGroupId(), $this->getSelectedCustomerGroups())) {
            return true;
        }

        return false;
    }

    /**
     * Check customer on list customer group.
     *
     * @return bool
     */
    public function canShow(): bool
    {
        if ($this->isEnable() && $this->checkGroupId()) {
            return true;
        }

        return false;
    }
}
