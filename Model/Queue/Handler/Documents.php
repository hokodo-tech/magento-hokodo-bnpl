<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
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
     * @var string[]
     */
    private $doctypeMap = [
        OrderDocumentInterface::TYPE_INVOICE => 'invoice',
        OrderDocumentInterface::TYPE_SHIPMENT => 'shipping',
        OrderDocumentInterface::TYPE_CREDIT_MEMO => 'credit_note',
    ];

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
                    $this->doctypeMap[$orderDocument->getDocumentType()],
                    $order->getPayment()->getAdditionalInformation()['hokodo_order_id']
                );
                $this->orderDocumentsRepository->save($orderDocument);
                $this->logger->info(__('Hokodo_BNPL: Invoice %1 was processed.', $document->getIncrementId()));
            }
        } catch (\Exception $e) {
            $this->logger->error(
                __(
                    'Hokodo_BNPL: Error processing %1 document - %2',
                    $orderDocument->getDocumentType(),
                    $e->getMessage()
                )
            );
        }
    }

    /**
     * Get document entity by entity type and id provided.
     *
     * @param int    $entityId
     * @param string $entityType
     *
     * @return CreditmemoInterface|InvoiceInterface|ShipmentInterface|null
     */
    private function getDocumentEntity(int $entityId, string $entityType)
    {
        switch ($entityType) {
            case OrderDocumentInterface::TYPE_INVOICE:
                return $this->invoiceRepository->get($entityId);
            case OrderDocumentInterface::TYPE_SHIPMENT:
                return $this->shipmentRepository->get($entityId);
            case OrderDocumentInterface::TYPE_CREDIT_MEMO:
                return $this->creditmemoRepository->get($entityId);
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
