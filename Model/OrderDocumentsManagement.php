<?php

/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Model;

use Hokodo\BNPL\Api\Data\OrderDocumentInterface;
use Hokodo\BNPL\Api\Data\OrderDocumentsInterfaceFactory;
use Hokodo\BNPL\Api\OrderDocumentsManagementInterface;
use Hokodo\BNPL\Model\Pdf\Invoice as PdfInvoice;
use Hokodo\BNPL\Service\OrderDocumentsService;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Filesystem;
use Magento\Framework\Filesystem\Directory\WriteInterface;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Sales\Api\Data\CreditmemoInterface;
use Magento\Sales\Api\Data\InvoiceInterface;
use Magento\Sales\Api\Data\ShipmentInterface;

/**
 * Class Hokodo\BNPL\Model\OrderDocumentsManagement.
 */
class OrderDocumentsManagement implements OrderDocumentsManagementInterface
{
    /**
     * @var OrderDocumentsInterfaceFactory
     */
    private OrderDocumentsInterfaceFactory $orderDocumentsInterfaceFactory;

    /**
     * @var OrderDocumentsService
     */
    private OrderDocumentsService $orderDocumentService;

    /**
     * @var DirectoryList
     */
    private DirectoryList $dir;

    /**
     * @var WriteInterface
     */
    private WriteInterface $outputDirectory;

    /**
     * @var DateTime
     */
    private DateTime $dateTime;

    /**
     * @var PdfInvoice
     */
    private PdfInvoice $pdfInvoice;

    /**
     * @param OrderDocumentsInterfaceFactory $orderDocumentsInterfaceFactory
     * @param OrderDocumentsService          $orderDocumentService
     * @param DirectoryList                  $dir
     * @param Filesystem                     $fileSystem
     * @param DateTime                       $dateTime
     * @param PdfInvoice                     $pdfInvoice
     *
     * @throws FileSystemException
     */
    public function __construct(
        OrderDocumentsInterfaceFactory $orderDocumentsInterfaceFactory,
        OrderDocumentsService $orderDocumentService,
        DirectoryList $dir,
        Filesystem $fileSystem,
        DateTime $dateTime,
        PdfInvoice $pdfInvoice
    ) {
        $this->orderDocumentsInterfaceFactory = $orderDocumentsInterfaceFactory;
        $this->orderDocumentService = $orderDocumentService;
        $this->dir = $dir;
        $this->outputDirectory = $fileSystem->getDirectoryWrite(DirectoryList::MEDIA);
        $this->dateTime = $dateTime;
        $this->pdfInvoice = $pdfInvoice;
    }

    /**
     * Set document to Hokodo.
     *
     * @param CreditmemoInterface|InvoiceInterface|ShipmentInterface $document
     * @param string                                                 $doctype
     * @param string                                                 $hokodoOrderId
     *
     * @return void
     *
     * @throws CouldNotSaveException
     * @throws FileSystemException
     * @throws \Zend_Pdf_Exception
     */
    public function setDocument($document, string $doctype, string $hokodoOrderId): void
    {
        $orderDocuments = $this->orderDocumentsInterfaceFactory->create();
        $orderDocuments
            ->setDocType($doctype)
            ->setDescription($document->getIncrementId())
            ->setAmount($document->getGrandTotal() * 100);
        switch ($doctype) {
            case OrderDocumentInterface::TYPE_INVOICE:
                $pdf = $this->pdfInvoice->getPdf([$document]);
                break;
            case OrderDocumentInterface::TYPE_SHIPMENT:
                $pdf = $this->pdfShipment->getPdf([$document]);
                break;
            case OrderDocumentInterface::TYPE_CREDIT_MEMO:
                $pdf = $this->pdfCreditmemo->getPdf([$document]);
                break;
            default:
                throw new CouldNotSaveException(__('Could not save PDF'));
        }
        $fileContent = $pdf->render();
        $fileName = sprintf($doctype . '%s.pdf', $this->dateTime->date('Y-m-d_H-i-s'));
        $this->outputDirectory->writeFile($fileName, $fileContent);
        $filePath = $this->dir->getPath(DirectoryList::MEDIA) . '/' . $fileName;
        $orderDocuments->setFilePath($filePath);
        $this->orderDocumentService->create($hokodoOrderId, $orderDocuments, $document->getStoreId());
        $this->outputDirectory->delete($this->dir->getPath(DirectoryList::MEDIA) . '/' . $fileName);
    }
}
