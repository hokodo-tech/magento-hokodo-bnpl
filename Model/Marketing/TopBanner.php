<?php

/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Hokodo\BNPL\Model\Marketing;

use Magento\Framework\View\Element\Block\ArgumentInterface;

class TopBanner implements BannerConfigProviderInterface, ArgumentInterface
{
    /**
     * @var array|mixed
     */
    private array $bannerTypes;

    /**
     * @param array $bannerTypes
     */
    public function __construct(
        array $bannerTypes = []
    ) {
        $this->bannerTypes = $bannerTypes;
    }

    /**
     * Get banner types from config.
     *
     * @return array
     */
    public function getBannerTypes(): array
    {
        return $this->bannerTypes;
    }

    /**
     * @inheritDoc
     */
    public function getBannerConfig(string $bannerType): array
    {
        return $this->bannerTypes[$bannerType] ?: [];
    }
}
