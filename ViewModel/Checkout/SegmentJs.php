<?php

/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Hokodo\BNPL\ViewModel\Checkout;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Component\ComponentRegistrar;
use Magento\Framework\Component\ComponentRegistrarInterface;
use Magento\Framework\Filesystem\Directory\ReadFactory;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;

class SegmentJs implements ArgumentInterface
{
    private const HOKODO_PAYMENT_ENV = 'payment/hokodo_bnpl/environment';

    private const SEGMENT_ENABLED = 'payment/hokodo_bnpl/segment/active';

    private const SEGMENT_SANDBOX_KEY_PATH = 'payment/hokodo_bnpl/segment/sandbox_key';

    private const SEGMENT_PROD_KEY_PATH = 'payment/hokodo_bnpl/segment/key';

    /**
     * @var ScopeConfigInterface
     */
    private $config;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var ComponentRegistrarInterface
     */
    private $componentRegistrar;

    /**
     * @var ReadFactory
     */
    private $readFactory;

    /**
     * SegmentJs constructor.
     *
     * @param ScopeConfigInterface        $config
     * @param StoreManagerInterface       $storeManager
     * @param ComponentRegistrarInterface $componentRegistrar
     * @param ReadFactory                 $readFactory
     */
    public function __construct(
        ScopeConfigInterface $config,
        StoreManagerInterface $storeManager,
        ComponentRegistrarInterface $componentRegistrar,
        ReadFactory $readFactory
    ) {
        $this->config = $config;
        $this->storeManager = $storeManager;
        $this->componentRegistrar = $componentRegistrar;
        $this->readFactory = $readFactory;
    }

    /**
     * Returns segment.js api key.
     *
     * @return string|null
     *
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getKey()
    {
        $storeId = $this->storeManager->getStore()->getId();
        switch ($this->config->getValue(
            self::HOKODO_PAYMENT_ENV,
            ScopeInterface::SCOPE_STORE,
            $storeId
        )) {
            case 'dev':
            case 'sandbox':
                return $this->config->getValue(self::SEGMENT_SANDBOX_KEY_PATH, ScopeInterface::SCOPE_STORE, $storeId);
            case 'production':
                return $this->config->getValue(self::SEGMENT_PROD_KEY_PATH, ScopeInterface::SCOPE_STORE, $storeId);
            default:
                return null;
        }
    }

    /**
     * Check is Analytics is enabled in config.
     *
     * @return mixed
     *
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function isAnalyticsEnabled()
    {
        $storeId = $this->storeManager->getStore()->getId();
        return $this->config->getValue(self::SEGMENT_ENABLED, ScopeInterface::SCOPE_STORE, $storeId);
    }

    /**
     * Get module composer version.
     *
     * @param string $moduleName
     *
     * @return Phrase|string|void
     */
    public function getModuleVersion($moduleName)
    {
        $path = $this->componentRegistrar->getPath(
            ComponentRegistrar::MODULE,
            $moduleName
        );
        $directoryRead = $this->readFactory->create($path);
        $composerJsonData = $directoryRead->readFile('composer.json');
        $data = json_decode($composerJsonData);

        return !empty($data->version) ? $data->version : __('Read error!');
    }
}
