<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Gateway\Request;

use GuzzleHttp\Psr7;
use Hokodo\BNPL\Api\Data\OrderDocumentsInterface;
use Hokodo\BNPL\Gateway\OrderDocumentsSubjectReader;
use Hokodo\BNPL\Model\SaveLog as PaymentLogger;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\ProductMetadataInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Filesystem\Directory\ReadFactory;
use Magento\Payment\Gateway\Request\BuilderInterface;

class CreateOrderDocumentsBuilder implements BuilderInterface
{
    /**
     * @const string Cache tag
     */
    private const MODULE_VERSION = '1.1.36';

    /**
     * @var OrderDocumentsSubjectReader
     */
    private $orderDocumentsSubjectReader;

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfiguration;

    /**
     * @var ProductMetadataInterface
     */
    private $productMetadata;

    /**
     * @var PaymentLogger
     */
    protected $paymentLogger;

    /**
     * @var ReadFactory
     */
    private $readFactory;

    /**
     * @param OrderDocumentsSubjectReader $orderDocumentsSubjectReader
     * @param ScopeConfigInterface        $scopeConfiguration
     * @param ProductMetadataInterface    $productMetadata
     * @param PaymentLogger               $paymentLogger
     * @param ReadFactory                 $readFactory
     */
    public function __construct(
        OrderDocumentsSubjectReader $orderDocumentsSubjectReader,
        ScopeConfigInterface $scopeConfiguration,
        ProductMetadataInterface $productMetadata,
        PaymentLogger $paymentLogger,
        ReadFactory $readFactory
    ) {
        $this->orderDocumentsSubjectReader = $orderDocumentsSubjectReader;
        $this->scopeConfiguration = $scopeConfiguration;
        $this->productMetadata = $productMetadata;
        $this->paymentLogger = $paymentLogger;
        $this->readFactory = $readFactory;
    }

    /**
     * @inheritDoc
     *
     * @see \Magento\Payment\Gateway\Request\BuilderInterface::build()
     */
    public function build(array $buildSubject)
    {
        return [
            'body' => $this->createOrderDocumentRequest($buildSubject),
        ];
    }

    /**
     * A function that create order request.
     *
     * @param array $buildSubject
     *
     * @return array
     *
     * @throws LocalizedException
     */
    private function createOrderDocumentRequest(array $buildSubject)
    {
        $document = $this->readDocument($buildSubject);
        $directory = $this->readFactory->create($document->getFilePath());
        $filePath = rtrim($directory->getAbsolutePath(), '/');
        $request = [
            [
                'name' => OrderDocumentsInterface::DOCUMENT_FILE,
                'contents' => Psr7\Utils::tryFopen($filePath, 'r'),
            ],
            [
                'name' => OrderDocumentsInterface::DOCUMENT_TYPE,
                'contents' => $document->getDocType(),
            ],
            [
                'name' => OrderDocumentsInterface::DOCUMENT_DESCRIPTION,
                'contents' => $document->getDescription(),
            ],
            [
                'name' => OrderDocumentsInterface::DOCUMENT_AMOUNT,
                'contents' => $document->getAmount(),
            ],
            [
                'name' => OrderDocumentsInterface::DOCUMENT_METADATA,
                'contents' => '{}',
            ],
        ];
        $data = [
            'payment_log_content' => [
                OrderDocumentsInterface::DOCUMENT_TYPE => $document->getDocType(),
                OrderDocumentsInterface::DOCUMENT_DESCRIPTION => $document->getDescription(),
                OrderDocumentsInterface::DOCUMENT_AMOUNT => $document->getAmount(),
                OrderDocumentsInterface::DOCUMENT_METADATA => 'Magento Version: ' . $this->productMetadata->getVersion()
                    . ', ' . 'Hokodo Module Version: ' . self::MODULE_VERSION . ', '
                    . 'PHP version: ' . phpversion(),
            ],
            'action_title' => 'CreateOrderDocumentsBuilder: createOrderDocumentRequest',
            'status' => 1,
        ];
        $this->paymentLogger->execute($data);

        return $request;
    }

    /**
     * A function that read invoice.
     *
     * @param array $buildSubject
     *
     * @throws \InvalidArgumentException
     *
     * @return CartInterface
     */
    private function readDocument(array $buildSubject)
    {
        return $this->orderDocumentsSubjectReader->readDocument($buildSubject);
    }
}
