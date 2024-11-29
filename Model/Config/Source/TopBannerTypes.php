<?php

/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Hokodo\BNPL\Model\Config\Source;

use Hokodo\BNPL\Model\Marketing\TopBanner;
use Magento\Framework\Data\OptionSourceInterface;

class TopBannerTypes implements OptionSourceInterface
{
    /**
     * @var TopBanner
     */
    private TopBanner $topBanner;

    /**
     * @param TopBanner $topBanner
     */
    public function __construct(
        TopBanner $topBanner
    ) {
        $this->topBanner = $topBanner;
    }

    /**
     * @inheritdoc
     */
    public function toOptionArray(): array
    {
        $bannerOptions = [];
        foreach ($this->topBanner->getBannerTypes() as $key => $bannerType) {
            $bannerOptions[] = [
                'label' => $bannerType['label'],
                'value' => $key,
            ];
        }

        return $bannerOptions;
    }
}
