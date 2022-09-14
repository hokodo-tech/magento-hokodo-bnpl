<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Hokodo\BNPL\Model\Config\Checkout\Provider;

use Hokodo\BNPL\Model\Config\Sdk as ConfigProvider;
use Magento\Checkout\Model\ConfigProviderInterface;
use Magento\Framework\Exception\LocalizedException;

class Sdk implements ConfigProviderInterface
{
    /**
     * @var ConfigProvider
     */
    private ConfigProvider $configProvider;

    /**
     * @param ConfigProvider $configProvider
     */
    public function __construct(
        ConfigProvider $configProvider
    ) {
        $this->configProvider = $configProvider;
    }

    /**
     * @inheritDoc
     *
     * @throws LocalizedException
     */
    public function getConfig()
    {
        return [
            'hokodoSdkKey' => $this->configProvider->getSdkKey(),
        ];
    }
}
