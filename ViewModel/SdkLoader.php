<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Hokodo\BNPL\ViewModel;

use Hokodo\BNPL\Gateway\Config\Config;
use Hokodo\BNPL\Model\Config\Sdk;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Locale\Resolver;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Store\Model\StoreManagerInterface;

class SdkLoader implements ArgumentInterface
{
    public const LOCALE_CODE_DEFAULT = 'en_GB';
    /**
     * @var Sdk
     */
    private Sdk $sdkConfig;

    /**
     * @var Config
     */
    private Config $paymentConfig;

    /**
     * @var StoreManagerInterface
     */
    private StoreManagerInterface $store;

    /**
     * @var Resolver
     */
    private Resolver $localeResolver;

    /**
     * @var Json
     */
    private Json $json;

    /**
     * @param Sdk                   $sdkConfig
     * @param Config                $paymentConfig
     * @param StoreManagerInterface $store
     * @param Resolver              $localeResolver
     * @param Json                  $json
     */
    public function __construct(
        Sdk $sdkConfig,
        Config $paymentConfig,
        StoreManagerInterface $store,
        Resolver $localeResolver,
        Json $json
    ) {
        $this->sdkConfig = $sdkConfig;
        $this->paymentConfig = $paymentConfig;
        $this->store = $store;
        $this->localeResolver = $localeResolver;
        $this->json = $json;
    }

    /**
     * Get config for UI component.
     *
     * @return string
     *
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function getJsConfig(): string
    {
        $config = [
            'url' => $this->sdkConfig->getSdkUrl(),
            'key' => $this->getSdkKey(),
            'sdkConfig' => [
                'locale' => $this->getCurrentLocaleCode(),
                'currency' => $this->store->getStore()->getCurrentCurrencyCode(),
            ],
        ];
        if ($faqUrl = $this->paymentConfig->getValue(Config::MARKETING_FAQ_LINK)) {
            $config['sdkConfig']['faqLink'] = $faqUrl;
        }
        if ($bannerLink = $this->paymentConfig->getValue(Config::MARKETING_BANNER_LINK)) {
            $config['sdkConfig']['bannerLink'] = $bannerLink;
        }

        return $this->json->serialize($config);
    }

    /**
     * Get SDK key for Search Component.
     *
     * @return string
     *
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function getSdkKey(): string
    {
        return $this->sdkConfig->getSdkKey();
    }

    /**
     * Get SDK url for Search Component.
     *
     * @return string
     *
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function getSdkUrl(): string
    {
        return $this->sdkConfig->getSdkUrl();
    }

    /**
     * Get Current Locale Code.
     *
     * @return string|null
     */
    public function getCurrentLocaleCode(): ?string
    {
        $code = $this->localeResolver->getLocale();
        $allowLocaleCodes = $this->getAllowLocaleCodes();
        if (isset($allowLocaleCodes[$code])) {
            return $allowLocaleCodes[$code];
        }

        return $allowLocaleCodes[self::LOCALE_CODE_DEFAULT];
    }

    /**
     * Get Allow Locale Codes.
     *
     * @return array
     */
    private function getAllowLocaleCodes(): ?array
    {
        return [
            'en_GB' => 'en-GB',
            'es_AR' => 'es',
            'es_BO' => 'es',
            'es_CL' => 'es',
            'es_CO' => 'es',
            'es_CR' => 'es',
            'es_MX' => 'es',
            'es_PA' => 'es',
            'es_PE' => 'es',
            'es_ES' => 'es',
            'es_US' => 'es',
            'es_VE' => 'es',
            'fr_BE' => 'fr',
            'fr_FR' => 'fr',
            'fr_LU' => 'fr',
            'fr_CH' => 'fr',
            'fr_CA' => 'fr',
            'de_AT' => 'de',
            'de_DE' => 'de',
            'de_LU' => 'de',
            'de_CH' => 'de',
            'nl_BE' => 'nl',
            'nl_NL' => 'nl',
        ];
    }

    /**
     * Check is module active.
     *
     * @return bool
     */
    public function isModuleActive(): bool
    {
        return $this->paymentConfig->isActive();
    }
}
