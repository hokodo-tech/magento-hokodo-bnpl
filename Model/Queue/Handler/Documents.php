<?php
/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Hokodo\BNPL\Model\Queue\Handler;

use Hokodo\BNPL\Api\Data\OrderDocumentInterface;
use Hokodo\BNPL\Api\OrderDocumentsRepositoryInterface;
use Hokodo\BNPL\Model\OrderDocumentsManagement;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Sales\Api\CreditmemoRepositoryInterface;
use Magento\Sales\Api\Data\CreditmemoInterface;
use Magento\Sales\Api\Data\InvoiceInterface;
use Magento\Sales\Api\Data\ShipmentInterface;
use Magento\Sales\Api\InvoiceRepositoryInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Api\ShipmentRepositoryInterface;
use Psr\Log\LoggerInterface;

class Documents
{
    public const TOPIC_NAME = 'hokodo.order.documents';

    /**
     * @var OrderRepositoryInterface
     */
    private OrderRepositoryInterface $orderRepository;

    /**
     * @var OrderDocumentsManagement
     */
    private OrderDocumentsManagement $orderDocumentsManagement;

    /**
     * @var InvoiceRepositoryInterface
     */
    private InvoiceRepositoryInterface $invoiceRepository;

    /**
     * @var ShipmentRepositoryInterface
     */
    private ShipmentRepositoryInterface $shipmentRepository;

    /**
     * @var CreditmemoRepositoryInterface
     */
    private CreditmemoRepositoryInterface $creditmemoRepository;

    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * @var OrderDocumentsRepositoryInterface
     */
    private OrderDocumentsRepositoryInterface $orderDocumentsRepository;

    /**
     * @var SearchCriteriaBuilder
     */
    private SearchCriteriaBuilder $searchCriteriaBuilder;

    /**
     * @param OrderRepositoryInterface          $orderRepository
     * @param OrderDocumentsRepositoryInterface $orderDocumentsRepository
     * @param SearchCriteriaBuilder             $searchCriteriaBuilder
     * @param OrderDocumentsManagement          $orderDocumentsManagement
     * @param InvoiceRepositoryInterface        $invoiceRepository
     * @param ShipmentRepositoryInterface       $shipmentRepository
     * @param CreditmemoRepositoryInterface     $creditmemoRepository
     * @param LoggerInterface                   $logger
     */
    public function __construct(
        OrderRepositoryInterface $orderRepository,
        OrderDocumentsRepositoryInterface $orderDocumentsRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        OrderDocumentsManagement $orderDocumentsManagement,
        InvoiceRepositoryInterface $invoiceRepository,
        ShipmentRepositoryInterface $shipmentRepository,
        CreditmemoRepositoryInterface $creditmemoRepository,
        LoggerInterface $logger
    ) {
        $this->orderRepository = $orderRepository;
        $this->orderDocumentsManagement = $orderDocumentsManagement;
        $this->invoiceRepository = $invoiceRepository;
        $this->shipmentRepository = $shipmentRepository;
        $this->creditmemoRepository = $creditmemoRepository;
        $this->logger = $logger;
        $this->orderDocumentsRepository = $orderDocumentsRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
    }

    /**
     * Execute queue message handler.
     *
     * @param OrderDocumentInterface $orderDocument
     *
     * @return void
     */
    public function execute(OrderDocumentInterface $orderDocument): void
    {
        try {
            if (!$this->isDocumentAlreadySent($orderDocument)) {
                $document = $this->getDocumentEntity(
                    (int) $orderDocument->getDocumentId(),
                    $orderDocument->getDocumentType()
                );

                $order = $this->orderRepository->get($orderDocument->getOrderId());
                $this->orderDocumentsManagement->setDocument(
                    $document,
                    $orderDocument->getDocumentType(),
                    $order->getPayment()->getAdditionalInformation()['hokodo_order_id']
                );
                $this->orderDocumentsRepository->save($orderDocument);
                $data = [
                    'message' => "Hokodo_BNPL: Invoice {$document->getIncrementId()} was processed.",
                ];
                $this->logger->info(__METHOD__, $data);
            }
        } catch (\Exception $e) {
            $data = [
                'message' => "Hokodo_BNPL: Error processing {$orderDocument->getDocumentType()} document.",
                'error' => $e->getMessage(),
            ];
            $this->logger->error(__METHOD__, $data);
        }
    }

    /**
     * Get document entity by entity type and id provided.
     *
     * @param int    $documentId
     * @param string $documentType
     *
     * @return CreditmemoInterface|InvoiceInterface|ShipmentInterface|null
     */
    private function getDocumentEntity(int $documentId, string $documentType)
    {
        switch ($documentType) {
            case OrderDocumentInterface::TYPE_INVOICE:
                return $this->invoiceRepository->get($documentId);
            case OrderDocumentInterface::TYPE_SHIPMENT:
                return $this->shipmentRepository->get($documentId);
            case OrderDocumentInterface::TYPE_CREDIT_MEMO:
                return $this->creditmemoRepository->get($documentId);
            default:
                return null;
        }
    }

    /**
     * Check is the record exists in DB.
     *
     * @param OrderDocumentInterface $orderDocument
     *
     * @return bool
     */
    public function isDocumentAlreadySent(OrderDocumentInterface $orderDocument)
    {
        return (bool) $this->orderDocumentsRepository->getList(
            $this->searchCriteriaBuilder
                ->addFilter(OrderDocumentInterface::ORDER_ID, $orderDocument->getOrderId())
                ->addFilter(OrderDocumentInterface::DOCUMENT_ID, $orderDocument->getDocumentId())
                ->addFilter(OrderDocumentInterface::DOCUMENT_TYPE, $orderDocument->getDocumentType())
                ->create()
        )->getTotalCount();
    }
}
