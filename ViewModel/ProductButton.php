<?php
/**
 * Copyright © 2018-2022 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Hokodo\BNPL\ViewModel;

use Hokodo\BNPL\Gateway\Config\Config;
use Magento\Framework\View\Element\Block\ArgumentInterface;

class ProductButton implements ArgumentInterface
{
    /**
     * @var Config
     */
    private $config;

    /**
     * ProductButton constructor.
     *
     * @param Config $config
     */
    public function __construct(
        Config $config
    ) {
        $this->config = $config;
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
}
