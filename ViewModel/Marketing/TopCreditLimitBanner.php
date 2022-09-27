<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Hokodo\BNPL\ViewModel\Marketing;

use Hokodo\BNPL\Gateway\Config\Config;
use Magento\Framework\View\Element\Block\ArgumentInterface;

class TopCreditLimitBanner implements ArgumentInterface
{
    /**
     * @var Config
     */
    private Config $paymentConfig;

    /**
     * @param Config $paymentConfig
     */
    public function __construct(
        Config $paymentConfig
    ) {
        $this->paymentConfig = $paymentConfig;
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
