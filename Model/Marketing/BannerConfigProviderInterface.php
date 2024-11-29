<?php

/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Hokodo\BNPL\Model\Marketing;

interface BannerConfigProviderInterface
{
    /**
     * Get banner config from di.
     *
     * @param string $bannerType
     *
     * @return array
     */
    public function getBannerConfig(string $bannerType): array;
}
