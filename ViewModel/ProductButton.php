<?php
/**
 * Copyright © 2018-2022 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Hokodo\BNPL\ViewModel;

use Hokodo\BNPL\Gateway\Config\Config;
use Magento\Framework\View\Element\Block\ArgumentInterface;

class ProductButton implements ArgumentInterface
{
    /**
     * @var Config
     */
    private $config;

    /**
     * ProductButton constructor.
     *
     * @param Config $config
     */
    public function __construct(
        Config $config
    ) {
        $this->config = $config;
    }

    /**
     * Get label for button from payment method config.
     *
     * @return string || null
     */
    public function getLabelBtn(): string
    {
        return $this->config->getValue(Config::KEY_BTN_PRODUCT_PAGE);
    }
}
