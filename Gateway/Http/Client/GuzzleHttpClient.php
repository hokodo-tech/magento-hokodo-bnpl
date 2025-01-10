<?php

/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Gateway\Http\Client;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use Hokodo\BNPL\Api\Data\OrderDocumentsInterface;
use Hokodo\BNPL\Service\LogObfuscatorService;
use Magento\Framework\Exception\LocalizedException;
use Magento\Payment\Gateway\Http\ClientException;
use Magento\Payment\Gateway\Http\ClientInterface;
use Magento\Payment\Gateway\Http\ConverterException;
use Magento\Payment\Gateway\Http\ConverterInterface;
use Magento\Payment\Gateway\Http\TransferInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface as Logger;

/**
 * Class Hokodo\BNPL\Gateway\Http\Client\GuzzleHttpClient.
 */
class GuzzleHttpClient implements ClientInterface
{
    /**
     * @var Client
     */
    private Client $client;

    /**
     * @var ConverterInterface|null
     */
    private ?ConverterInterface $converter;

    /**
     * @var Logger
     */
    private Logger $logger;

    /**
     * @var LogObfuscatorService
     */
    private LogObfuscatorService $logObfuscatorService;

    /**
     * @param LogObfuscatorService    $logObfuscatorService
     * @param Logger                  $logger
     * @param ConverterInterface|null $converter
     */
    public function __construct(
        LogObfuscatorService $logObfuscatorService,
        Logger $logger,
        ConverterInterface $converter = null
    ) {
        $this->logObfuscatorService = $logObfuscatorService;
        $this->client = new Client();
        $this->converter = $converter;
        $this->logger = $logger;
    }

    /**
     * @inheritDoc
     *
     * @throws LocalizedException
     *
     * @see ClientInterface::placeRequest()
     */
    public function placeRequest(TransferInterface $transferObject)
    {
        $log = [
            'method' => $transferObject->getMethod(),
            'request' => $transferObject->getBody(),
            'request_uri' => $transferObject->getUri(),
        ];
        $status = 0;
        $result = [];
        $headers = $transferObject->getHeaders();
        $dataType = 'json';
        $arraySearch = null;
        try {
            $data = [
                'headers' => $headers,
            ];

            if (!empty($transferObject->getBody()) && strpos($transferObject->getUri(), 'documents') !== false) {
                $columns = array_column($transferObject->getBody(), 'name');
                $arraySearch = array_search(OrderDocumentsInterface::DOCUMENT_FILE, $columns);
                unset($headers['Content-Type']);
                $data = [
                    'headers' => $headers,
                ];
                $dataType = 'multipart';
            }

            if ($transferObject->getAuthPassword() || $transferObject->getAuthUsername()) {
                $data['auth'] = [$transferObject->getAuthUsername(), $transferObject->getAuthPassword()];
            }
            $method = strtolower($transferObject->getMethod());
            switch (strtoupper($method)) {
                case 'POST':
                case 'PUT':
                case 'PATCH':
                    //add dataType header if only content is present
                    if ($transferObject->getBody()) {
                        $data[$dataType] = $transferObject->getBody();
                    }
                    break;
                case 'GET':
                case 'DELETE':
                case 'HEAD':
                    break;
            }

            /**
             * @var ResponseInterface $response
             */
            $response = $this->client->$method($transferObject->getUri(), $data);

            $result = $this->converter
                ? $this->converter->convert((string) $response->getBody())
                : [(string) $response->getBody()];
            $log['response'] = $result;
            $status = 1;
        } catch (BadResponseException $e) {
            $backtrace = debug_backtrace(); // @codingStandardsIgnoreLine
            array_shift($backtrace);
            foreach ($backtrace as $route) {
                if (isset($route['file']) && isset($route['class'])) {
                    $log['backtrace'][] = $route['file'] . "\t" . $route['line'];
                }
            }

            $log['response_status_code'] = $e->getCode();
            $log['response_status_message'] = $e->getMessage();

            if ($e instanceof \GuzzleHttp\Exception\ClientException && $e->hasResponse()) {
                $result = $e->getResponse();
                $body = $this->logObfuscatorService->execute((string) $result->getBody());
                $log['response'] = $this->converter
                ? $this->converter->convert($body)
                : [$body];
            } else {
                throw new ClientException(
                    __('Hokodo BNPL gateway has rejected request. Please try again later')
                );
            }
        } catch (ConverterException|LocalizedException $e) {
            $this->logger->error(
                __METHOD__,
                [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]
            );
        } finally {
            if (isset($arraySearch) && $arraySearch >= 0) {
                unset($log['request'][$arraySearch]);
            }
            unset($data);
            $data = [
                'payment_log_content' => $log,
                'action_title' => 'Gateway\Http\Client::class',
                'status' => $status,
            ];
            if ($status == 0) {
                $this->logger->error(__METHOD__, $data);
            } else {
                $this->logger->debug(__METHOD__, $data);
            }
        }

        return $result;
    }
}
