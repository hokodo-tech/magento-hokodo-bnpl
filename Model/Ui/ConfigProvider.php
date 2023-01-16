<?php
/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Hokodo\BNPL\Model\Ui;

use Hokodo\BNPL\Gateway\Config\Config;
use Hokodo\BNPL\Model\Adminhtml\Source\PaymentMethodLogos;
use Hokodo\BNPL\Model\Config\Source\HideHokodoOptions;
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
    private Config $config;

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
                    'hideHokodoPaymentType' => $this->getHideHokodoPaymentType(),
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
    private function isDefault(): bool
    {
        $result = false;
        $value = $this->config->getValue(Config::PAYMENT_DEFAULT);
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
    private function isForEligibleOrderOnly(): bool
    {
        $result = false;
        $value = $this->config->getValue(Config::PAYMENT_DEFAULT);
        if ($value == PaymentMethodBehaviour::IF_ORDER_ELIGIBLE) {
            $result = true;
        }
        return $result;
    }

    /**
     * Get Hide Hokodo Payment Type.
     *
     * @return string
     */
    private function getHideHokodoPaymentType(): string
    {
        $result = HideHokodoOptions::DONT_HIDE_CODE;
        if ($this->config->getValue(Config::HIDE_HOKODO)) {
            $hideHokodoConfigValue = $this->config->getValue(Config::HIDE_HOKODO_OPTIONS);
            if (!empty($hideHokodoConfigValue)) {
                $values = explode(',', $hideHokodoConfigValue);
                if (count($values) > 1) {
                    $result = HideHokodoOptions::BOTH_CODE;
                } elseif ($values[0] == HideHokodoOptions::ORDER_IS_NOT_ELIGIBLE) {
                    $result = HideHokodoOptions::ORDER_IS_NOT_ELIGIBLE_CODE;
                } elseif ($values[0] == HideHokodoOptions::COMPANY_IS_NOT_ATTACHED) {
                    $result = HideHokodoOptions::COMPANY_IS_NOT_ATTACHED_CODE;
                }
            }
        }
        return $result;
    }
}
