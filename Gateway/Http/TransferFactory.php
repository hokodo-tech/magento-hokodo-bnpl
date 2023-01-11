<?php
/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Gateway\Http;

use Hokodo\BNPL\Api\Data\OrderDocumentsInterface;
use Hokodo\BNPL\Gateway\Config\Config;
use Magento\Framework\Exception\LocalizedException;
use Magento\Payment\Gateway\Http\TransferBuilder;
use Magento\Payment\Gateway\Http\TransferFactoryInterface;
use Psr\Log\LoggerInterface as Logger;

/**
 * Class Hokodo\BNPL\Gateway\Http\TransferFactory.
 */
class TransferFactory implements TransferFactoryInterface
{
    /**
     * @var Config
     */
    private Config $config;

    /**
     * @var TransferBuilder
     */
    private TransferBuilder $transferBuilder;

    /**
     * @var Logger
     */
    private Logger $logger;

    /**
     * @param Config          $config
     * @param TransferBuilder $transferBuilder
     * @param Logger          $logger
     */
    public function __construct(
        Config $config,
        TransferBuilder $transferBuilder,
        Logger $logger
    ) {
        $this->config = $config;
        $this->transferBuilder = $transferBuilder;
        $this->logger = $logger;
    }

    /**
     * @inheritDoc
     *
     * @throws LocalizedException
     *
     * @see \Magento\Payment\Gateway\Http\TransferFactoryInterface::create()
     */
    public function create(array $request)
    {
        $method = $request['method'] ?? 'POST';
        $logBody = $body = $request['body'] ?? [];
        $isDocument = false;
        if (!empty($body) && strpos($request['uri'], 'documents') !== false) {
            $columns = array_column($body, 'name');
            $k = array_search(OrderDocumentsInterface::DOCUMENT_FILE, $columns);
            $isDocument = true;
            unset($logBody[$k]);
        }
        $log = [];
        $log['setClientConfig'] = $request['client_config'] ?? [];
        $log['setBody'] = $logBody;
        $log['setMethod'] = $method;
        $log['setUri'] = $this->getUri($request['uri'] ?? '');
        $data = [
            'payment_log_content' => $log,
            'action_title' => 'TransferFactory:: create',
            'status' => 0,
            'quote_id' => '',
        ];
        $this->logger->debug(__METHOD__, $data);
        return $this->transferBuilder
            ->setClientConfig($request['client_config'] ?? [])
            ->setHeaders(array_merge($this->getHeaders($isDocument), ($request['header'] ?? [])))
            ->setBody($body)
            ->setMethod($method)
            ->setUri($this->getUri($request['uri'] ?? ''))
            ->build();
    }

    /**
     * A function that get headers.
     *
     * @param bool $isDocument
     *
     * @return array
     */
    private function getHeaders($isDocument = false): array
    {
        return $isDocument ? [] : ['Content-Type' => 'application/json'];
    }

    /**
     * Returns URI.
     *
     * @param string $uri
     *
     * @return string
     *
     * @throws LocalizedException
     */
    private function getUri(string $uri): string
    {
        switch ($this->config->getEnvironment()) {
            case Config::ENV_DEV:
                $apiUri = $this->config->getDevUri();
                break;
            case Config::ENV_SANDBOX:
                $apiUri = $this->config->getSandboxUri();
                break;
            case Config::ENV_PRODUCTION:
                $apiUri = $this->config->getUri();
                break;
            default:
                throw new LocalizedException(__('Invalid environment'));
        }
        return $apiUri . $uri;
    }
}
