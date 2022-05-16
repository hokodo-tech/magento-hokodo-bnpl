<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Hokodo\BNPL\Controller\OnAuthorization;

use Hokodo\BNPL\Model\ResourceModel\Order;
use Hokodo\BNPL\Model\ResourceModel\PaymentQuote;
use Hokodo\BNPL\Model\SaveLog as PaymentLogger;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Payment\Model\Method\Logger;
use Magento\Quote\Model\QuoteFactory;

class Index implements ActionInterface
{
    private const RESULT_REDIRECT = 'hokodo_bnpl/OnAuthorization/success';
    /**
     * @var Order
     */
    private $order;
    /**
     * @var Context
     */
    private $context;
    /**
     * @var Session
     */
    private $session;
    /**
     * @var RedirectFactory
     */
    private $resultRedirectFactory;
    /**
     * @var Logger
     */
    private $logger;
    /**
     * @var PaymentLogger
     */
    private $paymentLogger;
    /**
     * @var PaymentQuote
     */
    private $paymentQuote;
    /**
     * @var QuoteFactory
     */
    private $quoteFactory;

    /**
     * A constructor.
     *
     * @param Context         $context
     * @param Session         $session
     * @param RedirectFactory $resultRedirectFactory
     * @param PaymentQuote    $paymentQuote
     * @param Order           $order
     * @param QuoteFactory    $quoteFactory
     * @param Logger          $logger
     * @param PaymentLogger   $paymentLogger
     */
    public function __construct(
        Context $context,
        Session $session,
        RedirectFactory $resultRedirectFactory,
        PaymentQuote $paymentQuote,
        Order $order,
        QuoteFactory $quoteFactory,
        Logger $logger,
        PaymentLogger $paymentLogger
    ) {
        $this->context = $context;
        $this->session = $session;
        $this->resultRedirectFactory = $resultRedirectFactory;
        $this->logger = $logger;
        $this->paymentLogger = $paymentLogger;
        $this->paymentQuote = $paymentQuote;
        $this->order = $order;
        $this->quoteFactory = $quoteFactory;
    }

    /**
     * A function that returns result redirect factory.
     *
     * @return mixed
     */
    public function execute()
    {
        $quoteId = $this->context->getRequest()->getParam('quoteId');
        $orderApiId = $this->context->getRequest()->getParam('orderApiId');
        $customerId = $this->context->getRequest()->getParam('customerId') ?: $this->session->getId();
        $qId = $this->paymentQuote->getByOrderId($orderApiId);
        $email = $this->order->getCustomerEmailFromOrderByQuoteId($qId);
        $log = [
            'class: ' . get_class($this),
            'orderApiId: ' . $orderApiId,
            'quoteId: ' . $quoteId,
            'paymentQuoteId: ' . $qId,
            'email: ' . $email,
            'customerId: ' . $customerId,
        ];
        $data = [
            'payment_log_content' => $log,
            'action_title' => 'On Authorization Index',
            'status' => 1,
            'quote_id' => $quoteId,
        ];
        $this->paymentLogger->execute($data);
        $parameters = ['quoteId' => $quoteId, 'orderApiId' => $orderApiId, 'customerId' => $customerId];
        return $this->resultRedirectFactory->create()->setPath(self::RESULT_REDIRECT, $parameters);
    }
}
