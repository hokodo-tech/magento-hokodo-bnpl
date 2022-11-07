<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Hokodo\BNPL\ViewModel\Marketing;

use Hokodo\BNPL\Gateway\Config\Config;
use Hokodo\BNPL\Service\CustomersGroup;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\View\Element\Block\ArgumentInterface;

class CreditLimitBanners implements ArgumentInterface
{
    /**
     * @var Config
     */
    private Config $paymentConfig;

    /**
     * @var CustomersGroup
     */
    private CustomersGroup $customersGroupService;

    /**
     * @var Json
     */
    private Json $json;

    /**
     * @var string|null
     */
    private ?string $bannerTypeStatic;

    /**
     * @var string|null
     */
    private ?string $bannerTypeCredit;

    /**
     * @param Config         $paymentConfig
     * @param CustomersGroup $customersGroupService
     * @param Json           $json
     */
    public function __construct(
        Config $paymentConfig,
        CustomersGroup $customersGroupService,
        Json $json
    ) {
        $this->paymentConfig = $paymentConfig;
        $this->customersGroupService = $customersGroupService;
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
        if ($this->bannerTypeStatic) {
            $config['bannerTypeStatic'] = $this->bannerTypeStatic;
        }
        if ($this->bannerTypeCredit) {
            $config['bannerTypeCredit'] = $this->bannerTypeCredit;
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
     * @param string|null $bannerTypeStatic
     *
     * @return CreditLimitBanners
     */
    public function setBannerTypeStatic(?string $bannerTypeStatic): self
    {
        $this->bannerTypeStatic = $bannerTypeStatic;
        return $this;
    }

    /**
     * Banner credit type setter.
     *
     * @param string|null $bannerTypeCredit
     *
     * @return CreditLimitBanners
     */
    public function setBannerTypeCredit(?string $bannerTypeCredit): self
    {
        $this->bannerTypeCredit = $bannerTypeCredit;
        return $this;
    }
}
