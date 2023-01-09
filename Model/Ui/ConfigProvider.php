<?php
/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Hokodo\BNPL\Model\Ui;

use Hokodo\BNPL\Gateway\Config\Config;
use Hokodo\BNPL\Model\Adminhtml\Source\PaymentMethodLogos;
use Hokodo\BNPL\Service\CustomersGroup;
use Magento\Checkout\Model\ConfigProviderInterface;
use Magento\Framework\App\RequestInterface;

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
     * @var RequestInterface
     */
    private $request;

    /**
     * @var CustomersGroup
     */
    private CustomersGroup $customersGroupService;

    /**
     * @param Config           $config
     * @param CustomersGroup   $customersGroupService
     * @param RequestInterface $request
     */
    public function __construct(
        Config $config,
        CustomersGroup $customersGroupService,
        RequestInterface $request
    ) {
        $this->config = $config;
        $this->request = $request;
        $this->customersGroupService = $customersGroupService;
    }

    /**
     * Retrieve assoc array of checkout configuration.
     *
     * @return array
     */
    public function getConfig()
    {
        $logos = $this->config->getValue(Config::PAYMENT_METHOD_LOGOS) ? PaymentMethodLogos::LOGOS_CLASS : [];
        if ($this->config->getValue(Config::PAYMENT_METHOD_DIRECT_LOGOS)) {
            $logos[] = $this->config->getValue(Config::PAYMENT_METHOD_DIRECT_LOGOS);
        }

        return [
            'payment' => [
                Config::CODE => [
                    'isActive' => $this->config->isActive() &&
                        $this->customersGroupService->isEnabledForCustomerGroup(),
                    'isDefault' => $this->request->getParam('payment_method') === Config::CODE ||
                        (bool) $this->config->getValue(Config::IS_PAYMENT_DEFAULT_PATH),
                    'title' => $this->config->getValue(Config::PAYMENT_TITLE),
                    'subtitle' => $this->config->getValue(Config::PAYMENT_SUBTITLE),
                    'hokodoLogo' => (bool) $this->config->getValue(Config::HOKODO_LOGO),
                    'logos' => $logos,
                    'moreInfo' => $this->config->getValue(Config::PAYMENT_MORE_INFO),
                ],
            ],
        ];
    }
}
