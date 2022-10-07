<?php
/**
 * Copyright © 2018-2022 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Hokodo\BNPL\ViewModel;

use Hokodo\BNPL\Gateway\Config\Config;
use Hokodo\BNPL\Service\CustomersGroup;
use Magento\Framework\View\Element\Block\ArgumentInterface;

class CartButton implements ArgumentInterface
{
    /**
     * @var Config
     */
    private $config;

    /**
     * @var CustomersGroup
     */
    private CustomersGroup $customersGroupService;

    /**
     * CartButton constructor.
     *
     * @param Config         $config
     * @param CustomersGroup $customersGroupService
     */
    public function __construct(
        Config $config,
        CustomersGroup $customersGroupService
    ) {
        $this->config = $config;
        $this->customersGroupService = $customersGroupService;
    }

    /**
     * Get label for button from payment method config.
     *
     * @return string || null
     */
    public function getLabelBtn(): string
    {
        return $this->config->getValue(Config::KEY_BTN_CART_PAGE_LABEL);
    }

    /**
     * Get status display for button from payment method config.
     *
     * @return bool
     */
    public function isEnable(): bool
    {
        return $this->config->isActive() && $this->customersGroupService->isEnabledForCustomerGroup();
    }

    /**
     * Get code of payment method.
     *
     * @return string
     */
    public function getCode(): string
    {
        return Config::CODE;
    }
}
