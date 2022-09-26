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
use Magento\Framework\View\Element\Block\ArgumentInterface;

class SdkLoader implements ArgumentInterface
{
    /**
     * @var Sdk
     */
    private Sdk $sdkConfig;

    /**
     * @var Config
     */
    private Config $paymentConfig;

    /**
     * @param Sdk    $sdkConfig
     * @param Config $paymentConfig
     */
    public function __construct(
        Sdk $sdkConfig,
        Config $paymentConfig
    ) {
        $this->sdkConfig = $sdkConfig;
        $this->paymentConfig = $paymentConfig;
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
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
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
}
