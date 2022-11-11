<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Hokodo\BNPL\ViewModel\Marketing;

use Hokodo\BNPL\Gateway\Config\Config;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\View\Element\Block\ArgumentInterface;

class CreditLimitBanners implements ArgumentInterface
{
    /**
     * @var Config
     */
    private Config $paymentConfig;

    /**
     * @var Json
     */
    private Json $json;

    /**
     * @var array
     */
    private array $bannerConfig;

    /**
     * @param Config $paymentConfig
     * @param Json   $json
     */
    public function __construct(
        Config $paymentConfig,
        Json $json
    ) {
        $this->paymentConfig = $paymentConfig;
        $this->json = $json;
    }

    /**
     * Get config for banner component.
     *
     * @return string
     */
    public function getJsConfig(): string
    {
        $config = [
            'customerGroupsEnabled' => $this->paymentConfig->isCustomerGroupsEnabled(),
            'customerGroups' => $this->paymentConfig->getCustomerGroups(),
            'topBannerTheme' => $this->paymentConfig->getMarketingTopBannerTheme(),
            'staticBannersEnabled' => $this->paymentConfig->isMarketingStaticBannersEnabled(),
            'creditBannersEnabled' => $this->paymentConfig->isMarketingCreditBannersEnabled(),
        ];
        if ($advertisedCreditAmount = $this->paymentConfig->getMarketingAdvertisedCreditAmount()) {
            $config['advertisedCreditAmount'] = $advertisedCreditAmount * 100;
        }
        foreach ($this->bannerConfig as $configName => $configValue) {
            $config[$configName] = $configValue;
        }

        return $this->json->serialize($config);
    }

    /**
     * Is module active.
     *
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->paymentConfig->isActive();
    }

    /**
     * Get theme for top banner.
     *
     * @return string
     */
    public function getTheme(): string
    {
        return $this->paymentConfig->getMarketingTopBannerTheme();
    }

    /**
     * Banner static type setter.
     *
     * @param array $bannerConfig
     *
     * @return CreditLimitBanners
     */
    public function setBannerConfig(array $bannerConfig): self
    {
        $this->bannerConfig = $bannerConfig;
        return $this;
    }
}
