<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Hokodo\BNPL\Model\Config;

use Hokodo\BNPL\Gateway\Config\Config;
use Magento\Framework\Exception\LocalizedException;
use Magento\Store\Model\StoreManagerInterface;

class Sdk
{
    public const SDK_PROD_URL = 'https://js.hokodo.co/hokodo-js/v1/';
    public const SDK_SANDBOX_URL = 'https://js-sandbox.hokodo.co/hokodo-js/v1/';
    public const SDK_DEV_URL = 'https://js-dev.hokodo.co/hokodo-js/v1/';
    /**
     * @var Config
     */
    private Config $config;

    /**
     * @var StoreManagerInterface
     */
    private StoreManagerInterface $storeManager;

    /**
     * @param Config                $config
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        Config $config,
        StoreManagerInterface $storeManager
    ) {
        $this->config = $config;
        $this->storeManager = $storeManager;
    }

    /**
     * Get Js Sdk key for frontend.
     *
     * @return string
     *
     * @throws LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getSdkKey(): string
    {
        $storeId = (int) $this->storeManager->getStore()->getId();
        switch ($this->config->getEnvironment($storeId)) {
            case Config::ENV_DEV:
                return $this->config->getDevSdkKey($storeId);
            case Config::ENV_SANDBOX:
                return $this->config->getSandboxSdkKey($storeId);
            case Config::ENV_PRODUCTION:
                return $this->config->getSdkKey($storeId);
            default:
                throw new LocalizedException(__('Invalid environment'));
        }
    }

    /**
     * Get load sdk url based on environment selected.
     *
     * @return string
     *
     * @throws LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getSdkUrl()
    {
        $storeId = (int) $this->storeManager->getStore()->getId();
        switch ($this->config->getEnvironment($storeId)) {
            case Config::ENV_DEV:
                return self::SDK_DEV_URL;
            case Config::ENV_SANDBOX:
                return self::SDK_SANDBOX_URL;
            case Config::ENV_PRODUCTION:
                return self::SDK_PROD_URL;
            default:
                throw new LocalizedException(__('Invalid environment'));
        }
    }
}
