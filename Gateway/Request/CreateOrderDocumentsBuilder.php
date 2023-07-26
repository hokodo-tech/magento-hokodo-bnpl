<?php
/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Gateway\Request;

use GuzzleHttp\Psr7;
use Hokodo\BNPL\Api\Data\OrderDocumentsInterface;
use Hokodo\BNPL\Gateway\SubjectReader;
use Magento\Framework\App\ProductMetadataInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Filesystem\Directory\ReadFactory;
use Magento\Payment\Gateway\Request\BuilderInterface;
use Psr\Log\LoggerInterface as Logger;

class CreateOrderDocumentsBuilder implements BuilderInterface
{
    /**
     * @var SubjectReader
     */
    private SubjectReader $subjectReader;

    /**
     * @var ProductMetadataInterface
     */
    private ProductMetadataInterface $productMetadata;

    /**
     * @var Logger
     */
    protected Logger $logger;

    /**
     * @var ReadFactory
     */
    private ReadFactory $readFactory;

    /**
     * @param SubjectReader            $subjectReader
     * @param ProductMetadataInterface $productMetadata
     * @param Logger                   $logger
     * @param ReadFactory              $readFactory
     */
    public function __construct(
        SubjectReader $subjectReader,
        ProductMetadataInterface $productMetadata,
        Logger $logger,
        ReadFactory $readFactory
    ) {
        $this->subjectReader = $subjectReader;
        $this->productMetadata = $productMetadata;
        $this->logger = $logger;
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
                    . ', ' . 'PHP version: ' . phpversion(),
            ],
            'action_title' => 'CreateOrderDocumentsBuilder: createOrderDocumentRequest',
            'status' => 1,
        ];
        $this->logger->debug(__METHOD__, $data);

        return $request;
    }

    /**
     * A function that read invoice.
     *
     * @param array $buildSubject
     *
     * @return OrderDocumentsInterface
     */
    private function readDocument(array $buildSubject): OrderDocumentsInterface
    {
        return $this->subjectReader->readFieldValue('document', $buildSubject);
    }
}
