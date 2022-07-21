<?php
/**
 * Copyright © 2018-2022 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Hokodo\BNPL\Plugin\CustomerData;

use Hokodo\BNPL\Gateway\Config\Config;
use Magento\Checkout\CustomerData\Cart as CartData;

class Cart
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
     * Check section data.
     *
     * @param CartData $subject
     * @param array    $result
     *
     * @return array
     */
    public function afterGetSectionData(CartData $subject, $result)
    {
        $result['hokodo_btn_show'] = $this->config->getValue(Config::KEY_BTN_CART_PAGE_ENABLE);
        $result['hokodo_btn_label'] = $this->config->getValue(Config::KEY_BTN_CART_PAGE_LABEL);
        $result['hokodo_payment_method_code'] = Config::CODE;

        return $result;
    }
}
