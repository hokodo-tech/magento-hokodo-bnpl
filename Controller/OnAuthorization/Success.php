<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Hokodo\BNPL\Controller\OnAuthorization;

use Exception;
use Hokodo\BNPL\Api\Data\OrderInformationInterfaceFactory;
use Hokodo\BNPL\Model\ResourceModel\PaymentQuote;
use Hokodo\BNPL\Model\ResourceModel\QuoteAddress;
use Hokodo\BNPL\Model\SaveLog as PaymentLogger;
use Hokodo\BNPL\Service\OrderService;
use Magento\Checkout\Api\GuestPaymentInformationManagementInterface;
use Magento\Checkout\Api\PaymentInformationManagementInterface;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Message\ManagerInterface;
use Magento\Payment\Model\Method\Logger;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Api\GuestCartRepositoryInterface;
use Magento\Quote\Model\QuoteFactory;
use Magento\Sales\Api\OrderRepositoryInterface;

class Success implements ActionInterface
{
    public const SUCCESS_PAGE_URL = 'checkout/onepage/success';
    public const CART_PAGE_URL = 'checkout/cart';
    /**
     * @var OrderInformationInterfaceFactory
     */
    private $orderInformationFactory;
    /**
     * @var PaymentQuote
     */
    private $paymentQuote;
    /**
     * @var QuoteAddress
     */
    private $quoteAddress;
    /**
     * @var OrderService
     */
    private $orderService;
    /**
     * @var CartRepositoryInterface
     */
    private $cartRepository;
    /**
     * @var GuestCartRepositoryInterface
     */
    private $guestCartRepository;
    /**
     * @var PaymentInformationManagementInterface
     */
    private $paymentInformationManagement;
    /**
     * @var GuestPaymentInformationManagementInterface
     */
    private $guestPaymentInformation;

    /**
     * @var QuoteFactory
     */
    private $quoteFactory;
    /**
     * @var Logger
     */
    private $logger;
    /**
     * @var PaymentLogger
     */
    private $paymentLogger;
    /**
     * @var OrderRepositoryInterface
     */
    private $orderRepository;
    /**
     * @var Context
     */
    private $context;
    /**
     * @var RedirectFactory
     */
    private $resultRedirectFactory;

    /**
     * @var ManagerInterface
     */
    protected $messageManager;

    /**
     * A constructor.
     *
     * @param Context                                    $context
     * @param OrderInformationInterfaceFactory           $orderInformationFactory
     * @param PaymentQuote                               $paymentQuote
     * @param QuoteAddress                               $quoteAddress
     * @param OrderService                               $orderService
     * @param GuestPaymentInformationManagementInterface $guestPaymentInformationManagement
     * @param PaymentInformationManagementInterface      $paymentInformationManagement
     * @param QuoteFactory                               $quoteFactory
     * @param CartRepositoryInterface                    $cartRepository
     * @param GuestCartRepositoryInterface               $guestCartRepository
     * @param OrderRepositoryInterface                   $orderRepository
     * @param Logger                                     $logger
     * @param PaymentLogger                              $paymentLogger
     * @param RedirectFactory                            $resultRedirectFactory
     * @param ManagerInterface                           $messageManager
     */
    public function __construct(
        Context $context,
        OrderInformationInterfaceFactory $orderInformationFactory,
        PaymentQuote $paymentQuote,
        QuoteAddress $quoteAddress,
        OrderService $orderService,
        GuestPaymentInformationManagementInterface $guestPaymentInformationManagement,
        PaymentInformationManagementInterface $paymentInformationManagement,
        QuoteFactory $quoteFactory,
        CartRepositoryInterface $cartRepository,
        GuestCartRepositoryInterface $guestCartRepository,
        OrderRepositoryInterface $orderRepository,
        Logger $logger,
        PaymentLogger $paymentLogger,
        RedirectFactory $resultRedirectFactory,
        ManagerInterface $messageManager
    ) {
        $this->context = $context;
        $this->orderInformationFactory = $orderInformationFactory;
        $this->paymentQuote = $paymentQuote;
        $this->quoteAddress = $quoteAddress;
        $this->orderService = $orderService;
        $this->guestPaymentInformation = $guestPaymentInformationManagement;
        $this->paymentInformationManagement = $paymentInformationManagement;
        $this->quoteFactory = $quoteFactory;
        $this->cartRepository = $cartRepository;
        $this->guestCartRepository = $guestCartRepository;
        $this->logger = $logger;
        $this->paymentLogger = $paymentLogger;
        $this->orderRepository = $orderRepository;
        $this->resultRedirectFactory = $resultRedirectFactory;
        $this->messageManager = $messageManager;
    }

    /**
     * A function that execute result redirect factory.
     *
     * @throws NoSuchEntityException
     * @throws CouldNotSaveException
     * @throws LocalizedException
     */
    public function execute()
    {
        $requestOrder = $this->orderInformationFactory->create();
        $request = $this->context->getRequest();
        $orderId = $request->getParam('orderApiId');
        $requestOrder->setId($orderId);
        $resultOrder = $this->orderService->get($requestOrder);
        $quoteId = $request->getParam('quoteId');
        $qId = $this->paymentQuote->getByOrderId($orderId);
        $magentoQuoteId = $this->paymentQuote->getMagentoQuoteIdByHokodoQuoteId($qId);
        $email = $resultOrder->getCustomer()->getInvoiceAddress()->getEmail();
        //Set quote email address
        $quote = $this->quoteFactory->create()->load($magentoQuoteId);
        if ($quote->getCustomerEmail() == '' && $email) {
            $quote->setData('customer_email', $email)->save();
            $this->quoteAddress->setCustomerEmailToQuoteAddressByQuoteId($magentoQuoteId, $email);
        }
        if ($resultOrder->getDeferredPayment()) {
            $quote = ($request->getParam('customerId') > 0) ?
                $this->cartRepository->get($quoteId) : $this->guestCartRepository->get($quoteId);
            $payment = $quote->getPayment();
            $payment->setAdditionalInformation('hokodo_deferred_payment_id', $resultOrder->getDeferredPayment());

            if ($request->getParam('customerId') > 0) {
                $orderId = $this->paymentInformationManagement->savePaymentInformationAndPlaceOrder($quoteId, $payment);
            } else {
                $orderId = $this->guestPaymentInformation->savePaymentInformationAndPlaceOrder(
                    $quoteId,
                    $email,
                    $payment
                );
            }
            try {
                $order = $this->orderRepository->get($orderId);
                $this->orderService->update($order);
                $this->paymentLogger->execute([
                    'payment_log_content' => [
                        get_class($this),
                        $quoteId,
                        $email,
                        $payment->getData('payment_quote_id'),
                    ],
                    'action_title' => 'OnAuthorization\Success: Updated',
                    'status' => 1,
                    'quote_id' => $quoteId,
                ]);
            } catch (Exception $ex) {
                $this->paymentLogger->execute([
                    'payment_log_content' => $ex->getMessage(),
                    'action_title' => 'OnAuthorization\Success: Exception',
                    'status' => 0,
                    'quote_id' => $quoteId,
                ]);
            }
            return $this->resultRedirectFactory->create()->setPath(self::SUCCESS_PAGE_URL);
        } else {
            $this->paymentLogger->execute([
                'payment_log_content' => [get_class($this), $quoteId, $email, __('Action not secure')],
                'action_title' => 'OnAuthorization\Success: Secure',
                'status' => 0,
            ]);
            $this->messageManager->addWarningMessage(__('Please complete the payment.'));
            return $this->resultRedirectFactory->create()->setPath(self::CART_PAGE_URL);
        }
    }
}
