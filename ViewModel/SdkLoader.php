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
     * @param Sdk                   $sdkConfig
     * @param Config                $paymentConfig
     * @param StoreManagerInterface $store
     * @param Resolver              $localeResolver
     */
    public function __construct(
        Sdk $sdkConfig,
        Config $paymentConfig,
        StoreManagerInterface $store,
        Resolver $localeResolver
    ) {
        $this->sdkConfig = $sdkConfig;
        $this->paymentConfig = $paymentConfig;
        $this->store = $store;
        $this->localeResolver = $localeResolver;
    }

    /**
     * Get Sdk url depending on selected environment.
     *
     * @return string
     *
     * @throws LocalizedException
     */
    public function getSdkUrl(): string
    {
        return $this->sdkConfig->getSdkUrl();
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
     * Get Url for faq on Hokodo marketing elements.
     *
     * @return string|null
     */
    public function getMarketingFaqUrl(): ?string
    {
        return $this->paymentConfig->getMarketingFaqUrl();
    }

    /**
     * Get Allow Locale Codes.
     *
     * @return array
     */
    public function getAllowLocaleCodes(): ?array
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
     * Get Current Currency Code.
     *
     * @return string|null
     *
     * @throws NoSuchEntityException
     */
    public function getCurrentCurrencyCode(): ?string
    {
        return $this->store->getStore()->getCurrentCurrencyCode();
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
