<?php
/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Gateway\Config;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Payment\Gateway\Config\Config as DefaultPaymentConfig;
use Magento\Store\Api\Data\StoreInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;

class Config extends DefaultPaymentConfig
{
    public const CODE = 'hokodo_bnpl';
    public const ENV_DEV = 'dev';
    public const ENV_SANDBOX = 'sandbox';
    public const ENV_PRODUCTION = 'production';
    public const KEY_ACTIVE = 'active';
    public const KEY_ENVIRONMENT = 'environment';
    public const KEY_DEV_URI = 'dev_uri';
    public const KEY_SANDBOX_URI = 'sandbox_uri';
    public const KEY_URI = 'uri';
    public const KEY_DEBUG = 'debug';
    public const KEY_DEV_API_KEY = 'dev_api_key';
    public const KEY_SANDBOX_API_KEY = 'sandbox_api_key';
    public const KEY_API_KEY = 'api_key';
    public const KEY_DEV_SDK_KEY = 'dev_sdk_key';
    public const KEY_SANDBOX_SDK_KEY = 'sandbox_sdk_key';
    public const KEY_SDK_KEY = 'sdk_key';
    public const KEY_ALLOW_SPECIFIC = 'allowspecific';
    public const KEY_SPECIFIC_COUNTRY = 'specificcountry';
    public const INVOICE_ON_PAYMENT = 'payment/hokodo_bnpl/create_invoice_on_payment_accepted';
    public const REPLACE_PLACE_ORDER_HOOKS = 'payment/hokodo_bnpl/replace_place_order_hooks';
    public const IS_PAYMENT_DEFAULT_PATH = 'payment/hokodo_bnpl/is_default';
    public const ALLOW_CUSTOMER_TO_CHANGE_COMPANY = 'payment/hokodo_bnpl/allow_customer_to_change_company';
    public const MARKETING_FAQ_LINK = 'marketing/faq_link';
    public const MARKETING_BANNER_LINK = 'marketing/banner_link';
    public const MARKETING_PRODUCT_PAGE_ENABLE = 'marketing/enable_product';
    public const MARKETING_TOP_BANNER_ENABLE = 'marketing/enable_top';
    public const MARKETING_TOP_BANNER_THEME = 'marketing/top_theme';
    public const MARKETING_STATIC_BANNERS_ENABLE = 'marketing/enable_static';
    public const MARKETING_CREDIT_BANNERS_ENABLE = 'marketing/enable_credit';
    public const MARKETING_ADVERTISED_CREDIT_AMOUNT = 'marketing/advertised_credit_amount';
    public const PAYMENT_TITLE = 'title';
    public const PAYMENT_SUBTITLE = 'marketing/subtitle';
    public const HOKODO_LOGO = 'marketing/hokodo_logo';
    public const PAYMENT_METHOD_LOGOS = 'marketing/payment_method_logos';
    public const PAYMENT_METHOD_DIRECT_LOGOS = 'marketing/payment_method_direct_logos';
    public const PAYMENT_MORE_INFO = 'marketing/payment_more_info';
    public const ENABLE_CUSTOMER_GROUPS = 'enable_customer_groups';
    public const CUSTOMER_GROUPS = 'customer_groups';
    public const CAPTURE_ONLINE = 'capture_online';
    public const TOTALS_FIX = 'advanced/enable_thirdparty_totals_compatibility';
    public const HOKODO_ENTITY_FOR_SAVE_COMPANY_LEVEL = 'advanced/entity_level';

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var StoreManagerInterface
     */
    private StoreManagerInterface $storeManager;

    /**
     * Config constructor.
     *
     * @param StoreManagerInterface $storeManager
     * @param ScopeConfigInterface  $scopeConfig
     * @param string                $methodCode
     * @param string                $pathPattern
     */
    public function __construct(
        StoreManagerInterface $storeManager,
        ScopeConfigInterface $scopeConfig,
        $methodCode = self::CODE,
        string $pathPattern = DefaultPaymentConfig::DEFAULT_PATH_PATTERN
    ) {
        parent::__construct($scopeConfig, $methodCode, $pathPattern);
        $this->scopeConfig = $scopeConfig;
        $this->storeManager = $storeManager;
    }

    /**
     * @inheritDoc
     *
     * @see \Magento\Payment\Gateway\Config\Config::getValue()
     */
    public function getValue($field, $storeId = null)
    {
        if ($storeId === null) {
            $storeId = $this->getStore()->getId();
        }
        // Check if "Set as default" setting is true and apply order change
        if ($field === 'sort_order' &&
            $this->scopeConfig->getValue(self::IS_PAYMENT_DEFAULT_PATH, ScopeInterface::SCOPE_STORE, $storeId)) {
            return 0;
        }
        return parent::getValue($field, $storeId);
    }

    /**
     * Allow customer to change company in customer backend.
     *
     * @param int $storeId
     *
     * @return bool
     */
    public function isAllowCustomerToChangeCompany(int $storeId = 0): bool
    {
        return $this->scopeConfig->isSetFlag(
            self::ALLOW_CUSTOMER_TO_CHANGE_COMPANY,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * A function that check is active.
     *
     * @param int|null $storeId
     *
     * @return bool
     */
    public function isActive(int $storeId = null): bool
    {
        return (bool) $this->getValue(self::KEY_ACTIVE, $storeId);
    }

    /**
     * Returns the current environment.
     *
     * @param int|null $storeId
     *
     * @return string
     */
    public function getEnvironment(int $storeId = null): string
    {
        return $this->getValue(self::KEY_ENVIRONMENT, $storeId);
    }

    /**
     * Returns development URI.
     *
     * @param int|null $storeId
     *
     * @return string
     */
    public function getDevUri(int $storeId = null): string
    {
        return $this->getValue(self::KEY_DEV_URI, $storeId);
    }

    /**
     * Returns sandbox URI.
     *
     * @param int|null $storeId
     *
     * @return string
     */
    public function getSandboxUri(int $storeId = null): string
    {
        return $this->getValue(self::KEY_SANDBOX_URI, $storeId);
    }

    /**
     * Returns production URI.
     *
     * @param int|null $storeId
     *
     * @return string
     */
    public function getUri(int $storeId = null): string
    {
        return $this->getValue(self::KEY_URI, $storeId);
    }

    /**
     * Returns is Debug Mode Enabled.
     *
     * @param int|null $storeId
     *
     * @return bool
     */
    public function isDebugEnabled(int $storeId = null): bool
    {
        return (bool) $this->getValue(self::KEY_DEBUG, $storeId);
    }

    /**
     * Returns development API key.
     *
     * @param int|null $storeId
     *
     * @return string
     */
    public function getDevApiKey(int $storeId = null): string
    {
        return $this->getValue(self::KEY_DEV_API_KEY, $storeId);
    }

    /**
     * Returns sandbox API key.
     *
     * @param int|null $storeId
     *
     * @return string
     */
    public function getSandboxApiKey(int $storeId = null): string
    {
        return $this->getValue(self::KEY_SANDBOX_API_KEY, $storeId);
    }

    /**
     * Returns production API key.
     *
     * @param int|null $storeId
     *
     * @return string
     */
    public function getApiKey(int $storeId = null): string
    {
        return $this->getValue(self::KEY_API_KEY, $storeId);
    }

    /**
     * Returns development API key.
     *
     * @param int|null $storeId
     *
     * @return string
     */
    public function getDevSdkKey(int $storeId = null): string
    {
        return $this->getValue(self::KEY_DEV_SDK_KEY, $storeId);
    }

    /**
     * Returns sandbox API key.
     *
     * @param int|null $storeId
     *
     * @return string
     */
    public function getSandboxSdkKey(int $storeId = null): string
    {
        return $this->getValue(self::KEY_SANDBOX_SDK_KEY, $storeId);
    }

    /**
     * Returns production API key.
     *
     * @param int|null $storeId
     *
     * @return string
     */
    public function getSdkKey(int $storeId = null): string
    {
        return $this->getValue(self::KEY_SDK_KEY, $storeId);
    }

    /**
     * A function that check is allowed specific.
     *
     * @param int|null $storeId
     *
     * @return bool
     */
    public function isAllowSpecific(int $storeId = null): bool
    {
        return (bool) $this->getValue(self::KEY_ALLOW_SPECIFIC, $storeId);
    }

    /**
     * A function that get specific country.
     *
     * @param int|null $storeId
     *
     * @return string
     */
    public function getSpecificCountry(int $storeId = null): string
    {
        return $this->getValue(self::KEY_SPECIFIC_COUNTRY);
    }

    /**
     * A function that get store.
     *
     * @return StoreInterface
     *
     * @throws NoSuchEntityException
     */
    private function getStore(): StoreInterface
    {
        return $this->storeManager->getStore();
    }

    /**
     * A function that create invoice automatically config.
     *
     * @param int|null $storeId
     *
     * @return bool
     */
    public function getCreateInvoiceAutomaticallyConfig(int $storeId = null): bool
    {
        return (bool) $this->scopeConfig->getValue(
            self::INVOICE_ON_PAYMENT,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Replaces Magento's place_order_hooks js requirement with Hokodo's config.
     *
     * @return bool
     */
    public function getReplacePlaceOrderHooksConfig(): bool
    {
        return (bool) $this->scopeConfig->getValue(
            self::REPLACE_PLACE_ORDER_HOOKS
        );
    }

    /**
     * Get Product page credit limit banner enabled.
     *
     * @return bool
     */
    public function isMarketingProductBannerEnabled(): bool
    {
        return (bool) $this->getValue(self::MARKETING_PRODUCT_PAGE_ENABLE);
    }

    /**
     * Get top credit limit banner enabled.
     *
     * @return bool
     */
    public function isMarketingTopBannerEnabled(): bool
    {
        return (bool) $this->getValue(self::MARKETING_TOP_BANNER_ENABLE);
    }

    /**
     * Get top credit limit banner theme.
     *
     * @return string
     */
    public function getMarketingTopBannerTheme(): string
    {
        return $this->getValue(self::MARKETING_TOP_BANNER_THEME);
    }

    /**
     * Check is customer groups enabled.
     *
     * @return bool
     */
    public function isCustomerGroupsEnabled(): bool
    {
        return (bool) $this->getValue(self::ENABLE_CUSTOMER_GROUPS);
    }

    /**
     * Get customer groups array.
     *
     * @return array
     */
    public function getCustomerGroups(): array
    {
        return explode(',', $this->getValue(self::CUSTOMER_GROUPS) ?? '');
    }

    /**
     * Get mar.
     *
     * @return bool
     */
    public function isMarketingStaticBannersEnabled(): bool
    {
        return (bool) $this->getValue(self::MARKETING_STATIC_BANNERS_ENABLE);
    }

    /**
     * Get customer groups array.
     *
     * @return bool
     */
    public function isMarketingCreditBannersEnabled(): bool
    {
        return (bool) $this->getValue(self::MARKETING_CREDIT_BANNERS_ENABLE);
    }

    /**
     * Get credit amount for banner advertisement.
     *
     * @return int|null
     */
    public function getMarketingAdvertisedCreditAmount(): ?int
    {
        if (($value = $this->getValue(self::MARKETING_ADVERTISED_CREDIT_AMOUNT)) && $value != 0) {
            return $value;
        }

        return null;
    }

    /**
     * Returns the current data source for company id.
     *
     * @param int $storeId
     *
     * @return string
     */
    public function getEntityLevel(): string
    {
        return (string) $this->getValue(
            self::HOKODO_ENTITY_FOR_SAVE_COMPANY_LEVEL
        );
    }
}
