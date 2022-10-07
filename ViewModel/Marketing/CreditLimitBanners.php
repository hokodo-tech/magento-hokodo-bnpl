<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Hokodo\BNPL\ViewModel\Marketing;

use Hokodo\BNPL\Gateway\Config\Config;
use Hokodo\BNPL\Service\CustomersGroup;
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
     * @param Config         $paymentConfig
     * @param CustomersGroup $customersGroupService
     */
    public function __construct(
        Config $paymentConfig,
        CustomersGroup $customersGroupService
    ) {
        $this->paymentConfig = $paymentConfig;
        $this->customersGroupService = $customersGroupService;
    }

    /**
     * Is top marketing banner enabled.
     *
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->paymentConfig->isActive() && $this->customersGroupService->isEnabledForCustomerGroup();
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
}
