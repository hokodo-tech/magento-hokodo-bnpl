<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Hokodo\BNPL\Gateway\Request;

use Exception;
use Hokodo\BNPL\Gateway\Config\Config;
use Magento\Framework\Exception\LocalizedException;
use Magento\Payment\Gateway\Request\BuilderInterface;

/**
 * Class Hokodo\BNPL\Gateway\Request\AuthorizationBuilder.
 */
class AuthorizationBuilder implements BuilderInterface
{
    /**
     * @var Config
     */
    private $config;

    /**
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * @inheritDoc
     *
     * @throws Exception
     *
     * @see \Magento\Payment\Gateway\Request\BuilderInterface::build()
     */
    public function build(array $buildSubject): array
    {
        return [
            'header' => [
                'Authorization' => sprintf('Token %s', $this->getAuthorizationKey()),
            ],
        ];
    }

    /**
     * Returns authorization key.
     *
     * @return string
     *
     * @throws LocalizedException
     */
    private function getAuthorizationKey(): string
    {
        switch ($this->config->getEnvironment()) {
            case Config::ENV_DEV:
                return $this->config->getDevApiKey();
            case Config::ENV_SANDBOX:
                return $this->config->getSandboxApiKey();
            case Config::ENV_PRODUCTION:
                return $this->config->getApiKey();
            default:
                throw new LocalizedException(__('Invalid environment'));
        }
    }
}
