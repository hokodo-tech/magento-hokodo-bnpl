<?php
/**
 * Copyright © 2018-2022 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Hokodo\BNPL\Block;

use Hokodo\BNPL\Gateway\Config\Config;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

class ProductButton extends Template
{
    /**
     * @var Config
     */
    private $config;

    /**
     * ProductButton constructor.
     *
     * @param Context $context
     * @param Config  $config
     */
    public function __construct(
        Context $context,
        Config $config
    ) {
        $this->config = $config;
        parent::__construct($context);
    }

    /**
     * Get label for button from payment method config.
     *
     * @return string
     */
    public function getLabelBtn(): string
    {
        return $this->config->getValue(Config::KEY_BTN_PRODUCT_PAGE_LABEL);
    }

    /**
     * Get status display for button from payment method config.
     *
     * @return bool
     */
    public function isEnable(): bool
    {
        if ($this->config->getValue(Config::KEY_BTN_PRODUCT_PAGE_ENABLE) == '1') {
            return true;
        }

        return false;
    }
}
