<?php

namespace Hokodo\BNPL\Service;

use Magento\Framework\DB\Transaction;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Model\Order\Email\Sender\InvoiceSender;
use Magento\Sales\Model\Service\InvoiceService;

class InvoiceCreatorService
{
    /**
     * @var OrderRepositoryInterface @orderRepository
     */
    protected $orderRepository;
    /**
     * @var InvoiceService @invoiceService
     */
    protected $invoiceService;
    /**
     * @var Transaction @transaction
     */
    protected $transaction;
    /**
     * @var InvoiceSender @invoiceSender
     */
    protected $invoiceSender;

    /**
     * A construct.
     *
     * @param OrderRepositoryInterface $orderRepository
     * @param InvoiceService           $invoiceService
     * @param InvoiceSender            $invoiceSender
     * @param Transaction              $transaction
     */
    public function __construct(
        OrderRepositoryInterface $orderRepository,
        InvoiceService $invoiceService,
        InvoiceSender $invoiceSender,
        Transaction $transaction
    ) {
        $this->orderRepository = $orderRepository;
        $this->invoiceService = $invoiceService;
        $this->transaction = $transaction;
        $this->invoiceSender = $invoiceSender;
    }

    /**
     * The function that issues the invoice.
     *
     * @param string $orderId
     *
     * @return void
     */
    public function execute($orderId)
    {
        $order = $this->orderRepository->get($orderId);
        if ($order->canInvoice()) {
            $invoice = $this->invoiceService->prepareInvoice($order);
            $invoice->register();
            $invoice->save();
            $transactionSave = $this->transaction->addObject(
                $invoice
            )->addObject(
                $invoice->getOrder()
            );
            $transactionSave->save();
            $this->invoiceSender->send($invoice);
            $order->addStatusHistoryComment(
                __('Notified customer about invoice creation #%1.', $invoice->getId())
            )
                ->setIsCustomerNotified(true)
                ->save();
        }
    }
}
