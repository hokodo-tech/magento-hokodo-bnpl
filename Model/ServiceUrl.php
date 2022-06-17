<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Model;

use Magento\Framework\UrlInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Store\Model\StoreRepository;

/**
 * Class Hokodo\BNPL\Model\ServiceUrl.
 */
class ServiceUrl
{
    /**
     * @var UrlInterface
     */
    private $url;

    /**
     * @var string
     */
    private $service;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var string
     */
    private $version;

    /**
     * @var StoreRepository
     */
    private $storeRepository;

    /**
     * @param UrlInterface          $url
     * @param StoreManagerInterface $storeManager
     * @param StoreRepository       $storeRepository
     * @param string                $service
     * @param string                $version
     */
    public function __construct(
        UrlInterface $url,
        StoreManagerInterface $storeManager,
        StoreRepository $storeRepository,
        $service = 'rest',
        $version = 'V1'
    ) {
        $this->url = $url;
        $this->service = $service;
        $this->storeManager = $storeManager;
        $this->version = $version;
        $this->storeRepository = $storeRepository;
    }

    /**
     * A function that gets url address.
     *
     * @param string $path
     *
     * @return string
     */
    public function getUrl($path)
    {
        return $this->getServiceUrl() . ltrim($path, '/');
    }

    /**
     * Get url without store code.
     *
     * @param string $path
     *
     * @return string
     */
    public function getUrlWithoutStoreCode($path)
    {
        $currentStore = $this->storeManager->getStore();
        $store = $this->storeRepository->getById($currentStore->getId());
        /* Get Current Store Code */
        $baseUrl = $currentStore->getBaseUrl(
            \Magento\Framework\UrlInterface::URL_TYPE_LINK
        );
        $baseUrl = str_replace($store->getCode() . '/', '', $baseUrl);
        $serviceUrl = $this->service . '/' . $store->getCode() . '/' . $this->version;
        return $baseUrl . $serviceUrl . '/' . ltrim($path, '/');
    }

    /**
     * Prepare rest suffix for url. For example rest/default/V1.
     *
     * @return string
     */
    private function getServiceUrl()
    {
        $store = $this->storeRepository->getById($this->storeManager->getStore()->getId());
        return $this->url->getUrl(
            $this->service . '/' . $store->getCode() . '/' . $this->version
        );
    }
}
