<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Hokodo\BNPL\ViewModel;

use Hokodo\BNPL\Model\Config\Sdk;
use Magento\Framework\View\Element\Block\ArgumentInterface;

class CompanySearch implements ArgumentInterface
{
    /**
     * @var Sdk
     */
    private Sdk $sdkConfig;

    /**
     * @param Sdk $sdkConfig
     */
    public function __construct(
        Sdk $sdkConfig
    ) {
        $this->sdkConfig = $sdkConfig;
    }
}
