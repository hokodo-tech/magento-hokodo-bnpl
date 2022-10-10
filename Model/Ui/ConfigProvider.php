<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Hokodo\BNPL\Model\Ui;

use Hokodo\BNPL\Gateway\Config\Config;
use Hokodo\BNPL\Service\CustomersGroup;
use Magento\Checkout\Model\ConfigProviderInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Exception\NoSuchEntityException;

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
     *
     * @throws NoSuchEntityException
     */
    public function getConfig()
    {
        $isActive = $this->config->isActive() && $this->customersGroupService->isEnabledForCustomerGroup();

        $config = [
            'isActive' => $isActive,
        ];
        $config['isDefault'] = (bool) $this->config->getValue(Config::IS_PAYMENT_DEFAULT_PATH);
        if ($this->request->getParam('payment_method') === Config::CODE) {
            $config['isDefault'] = true;
        }

        return [
            'payment' => [
                Config::CODE => $config,
            ],
        ];
    }
}
