<?php
/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Hokodo\BNPL\Model\Ui;

use Hokodo\BNPL\Gateway\Config\Config;
use Hokodo\BNPL\Model\Adminhtml\Source\PaymentMethodLogos;
use Hokodo\BNPL\Model\Config\Source\PaymentMethodBehaviour;
use Hokodo\BNPL\Service\CustomersGroup;
use Magento\Checkout\Model\ConfigProviderInterface;

/**
 * Class Hokodo\BNPL\Model\Ui\ConfigProvider.
 */
class ConfigProvider implements ConfigProviderInterface
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
     * Retrieve assoc array of checkout configuration.
     *
     * @return array
     */
    public function getConfig(): array
    {
        $logos = $this->config->getValue(Config::PAYMENT_METHOD_LOGOS) ? PaymentMethodLogos::LOGOS_CLASS : [];
        if ($this->config->getValue(Config::PAYMENT_METHOD_DIRECT_LOGOS)) {
            $logos[] = $this->config->getValue(Config::PAYMENT_METHOD_DIRECT_LOGOS);
        }

        return [
            'payment' => [
                Config::CODE => [
                    'paymentMethodCode' => Config::CODE,
                    'isActive' => $this->config->isActive() &&
                        $this->customersGroupService->isEnabledForCustomerGroup(),
                    'isDefault' => $this->isDefault(),
                    'isForEligibleOrderOnly' => $this->isForEligibleOrderOnly(),
                    'title' => $this->config->getValue(Config::PAYMENT_TITLE),
                    'subtitle' => $this->config->getValue(Config::PAYMENT_SUBTITLE),
                    'hokodoLogo' => (bool) $this->config->getValue(Config::HOKODO_LOGO),
                    'logos' => $logos,
                    'moreInfo' => $this->config->getValue(Config::PAYMENT_MORE_INFO),
                ],
            ],
        ];
    }

    /**
     * Checks is payment method was set as default.
     *
     * @return bool
     */
    public function isDefault(): bool
    {
        $result = false;
        $value = $this->config->getValue(Config::IS_PAYMENT_DEFAULT_PATH);
        if ($value == PaymentMethodBehaviour::IS_DEFAULT_YES) {
            $result = true;
        }
        return $result;
    }

    /**
     * Checks is payment method was set for Eligible orders only.
     *
     * @return bool
     */
    public function isForEligibleOrderOnly(): bool
    {
        $result = false;
        $value = $this->config->getValue(Config::IS_PAYMENT_DEFAULT_PATH);
        if ($value == PaymentMethodBehaviour::IF_ORDER_ELIGIBLE) {
            $result = true;
        }
        return $result;
    }
}
