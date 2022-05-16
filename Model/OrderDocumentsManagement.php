<?php

/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Model;

use Hokodo\BNPL\Api\Data\OrderDocumentsInterface;
use Hokodo\BNPL\Api\OrderDocumentsManagementInterface;
use Hokodo\BNPL\Model\Pdf\Invoice as PdfInvoice;
use Hokodo\BNPL\Model\Pdf\Shipment as PdfShipment;
use Hokodo\BNPL\Model\SaveLog as PaymentLogger;
use Hokodo\BNPL\Service\OrderDocumentsService;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Filesystem;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Sales\Api\Data\InvoiceInterface;
use Magento\Sales\Api\Data\ShipmentInterface;
use Magento\Sales\Api\InvoiceRepositoryInterface;
use Magento\Sales\Api\ShipmentRepositoryInterface;
use Magento\Sales\Model\Order;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class Hokodo\BNPL\Model\OrderDocumentsManagement.
 */
class OrderDocumentsManagement implements OrderDocumentsManagementInterface
{
    /**
     * @var PaymentLogger
     */
    private $paymentLogger;

    /**
     * @var OrderDocumentsInterface
     */
    private $orderDocumentsInterface;

    /**
     * @var OrderDocumentsService
     */
    private $orderDocumentService;

    /**
     * @var DirectoryList
     */
    private $dir;

    /**
     * @var Filesystem
     */
    private $outputDirectory;

    /**
     * @var DateTime
     */
    private $dateTime;

    /**
     * @var PdfInvoice
     */
    private $pdfInvoice;

    /**
     * @var PdfShipment
     */
    private $pdfShipment;

    /**
     * @var InvoiceRepositoryInterface
     */
    private $invoiceRepository;

    /**
     * @var ShipmentRepositoryInterface
     */
    private $shipmentRepository;

    /**
     * @var SearchCriteriaBuilder
     */
    protected $searchCriteriaBuilder;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @param PaymentLogger               $paymentLogger
     * @param OrderDocumentsInterface     $orderDocumentsInterface
     * @param OrderDocumentsService       $orderDocumentService
     * @param DirectoryList               $dir
     * @param Filesystem                  $fileSystem
     * @param DateTime                    $dateTime
     * @param PdfInvoice                  $pdfInvoice
     * @param PdfShipment                 $pdfShipment
     * @param InvoiceRepositoryInterface  $invoiceRepository
     * @param ShipmentRepositoryInterface $shipmentRepository
     * @param SearchCriteriaBuilder       $searchCriteriaBuilder
     * @param StoreManagerInterface       $storeManager
     */
    public function __construct(
        PaymentLogger $paymentLogger,
        OrderDocumentsInterface $orderDocumentsInterface,
        OrderDocumentsService $orderDocumentService,
        DirectoryList $dir,
        Filesystem $fileSystem,
        DateTime $dateTime,
        PdfInvoice $pdfInvoice,
        PdfShipment $pdfShipment,
        InvoiceRepositoryInterface $invoiceRepository,
        ShipmentRepositoryInterface $shipmentRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        StoreManagerInterface $storeManager
    ) {
        $this->paymentLogger = $paymentLogger;
        $this->orderDocumentsInterface = $orderDocumentsInterface;
        $this->orderDocumentService = $orderDocumentService;
        $this->dir = $dir;
        $this->outputDirectory = $fileSystem->getDirectoryWrite(DirectoryList::MEDIA);
        $this->dateTime = $dateTime;
        $this->pdfInvoice = $pdfInvoice;
        $this->pdfShipment = $pdfShipment;
        $this->invoiceRepository = $invoiceRepository;
        $this->shipmentRepository = $shipmentRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->storeManager = $storeManager;
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\OrderDocumentsManagementInterface::setDocuments()
     */
    public function setDocuments(
        Order $order,
        $doctype = 'invoice'
    ) {
        /**
         * @var \Magento\Sales\Model\Order $order
         */
        $orderId = $order->getId();
        try {
            if ($doctype == 'invoice') {
                $orderDocumentItems = $this->getInvoiceDataByOrderId($orderId);
            } else {
                $orderDocumentItems = $this->getShipmentDataByOrderId($orderId);
            }
            if ($order && $orderDocumentItems) {
                /**
                 * @var OrderDocumentsInterface
                 */
                $orderDocuments = $this->orderDocumentsInterface;
                $orderDocuments->setDocType($doctype);
                $docDescription = [];
                $amount = 0;
                foreach ($orderDocumentItems as $document) {
                    $docDescription[] = $document->getIncrementId();
                    $amount += $document->getGrandTotal();
                }
                $orderDocuments->setDescription('#' . implode(', #', $docDescription));
                $orderDocuments->setAmount($amount * 100);
                //Generate PDF file to var directory
                if ($doctype == 'invoice') {
                    $pdf = $this->pdfInvoice->getPdf($orderDocumentItems);
                } else {
                    $pdf = $this->pdfShipment->getPdf($orderDocumentItems);
                }
                $fileContent = $pdf->render();
                $fileName = sprintf($doctype . '%s.pdf', $this->dateTime->date('Y-m-d_H-i-s'));
                $this->outputDirectory->writeFile($fileName, $fileContent);
                $filePath = $this->dir->getPath(DirectoryList::MEDIA) . '/' . $fileName;
                $orderDocuments->setFilePath($filePath);
                //Create Hokodo order document
                /**
                 * @var OrderDocumentsInterface
                 */
                $result = $this->orderDocumentService->create($order->getOrderApiId(), $orderDocuments);
                if ($result->getId()) {
                    $data = [
                        'payment_log_content' => [
                            'Document type: ' . $doctype,
                            'Hokodo Document id: ' . $result->getId(),
                            'Order id: ' . $order->getOrderApiId(),
                        ],
                        'action_title' => 'OrderDocumentsManagement::setDocuments() Success',
                        'status' => 1,
                        'quote_id' => $order->getEntityId(),
                    ];
                    $this->paymentLogger->execute($data);
                }
                $this->outputDirectory->delete($this->dir->getPath(DirectoryList::MEDIA) . '/' . $fileName);
            }
        } catch (LocalizedException $e) {
            $data = [
                'payment_log_content' => 'LocalizedException: ' . $e->getMessage(),
                'action_title' => 'OrderDocumentsManagement::setDocuments() LocalizedException',
                'status' => 0,
                'quote_id' => $orderId,
            ];
            $this->paymentLogger->execute($data);
            throw new CouldNotSaveException(
                __('LocalizedException: ' . $e->getMessage()),
                $e
            );
        } catch (\Exception $e) {
            $data = [
                'payment_log_content' => 'Exception: ' . $e->getMessage(),
                'action_title' => 'OrderDocumentsManagement::setDocuments() Exception',
                'status' => 0,
                'quote_id' => $orderId,
            ];
            $this->paymentLogger->execute($data);
            throw new CouldNotSaveException(
                __('Exception: ' . $e->getMessage()),
                $e
            );
        }

        return $result;
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\OrderDocumentsManagementInterface::getDocuments()
     */
    public function getDocuments($docId, $orderId)
    {
        return null;
    }

    /**
     * Get Invoice data by Order Id.
     *
     * @param int $orderId
     *
     * @return InvoiceInterface[]|null
     */
    public function getInvoiceDataByOrderId(int $orderId)
    {
        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter('order_id', $orderId)->create();
        try {
            $invoices = $this->invoiceRepository->getList($searchCriteria);
            $invoiceRecords = $invoices->getItems();
        } catch (\Exception $exception) {
            $data = [
                'payment_log_content' => 'Exception: ' . $exception->getMessage(),
                'action_title' => 'OrderDocumentsManagement::getInvoiceDataByOrderId() Exception',
                'status' => 0,
                'quote_id' => $orderId,
            ];
            $this->paymentLogger->execute($data);
            $invoiceRecords = null;
        }
        return $invoiceRecords;
    }

    /**
     * Get Invoice data by Order Id.
     *
     * @param int $orderId
     *
     * @return ShipmentInterface[]|null
     */
    public function getShipmentDataByOrderId(int $orderId)
    {
        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter('order_id', $orderId)->create();
        try {
            $shipments = $this->shipmentRepository->getList($searchCriteria);
            $shipmentRecords = $shipments->getItems();
        } catch (\Exception $exception) {
            $data = [
                'payment_log_content' => 'Exception: ' . $exception->getMessage(),
                'action_title' => 'OrderDocumentsManagement::getShipmentDataByOrderId() Exception',
                'status' => 0,
                'quote_id' => $orderId,
            ];
            $this->paymentLogger->execute($data);
            $shipmentRecords = null;
        }
        return $shipmentRecords;
    }
}
