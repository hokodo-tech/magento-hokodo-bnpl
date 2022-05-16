<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Observer;

use Hokodo\BNPL\Api\Data\PaymentQuoteInterface;
use Hokodo\BNPL\Api\HokodoOrganisationRepositoryInterface;
use Hokodo\BNPL\Api\PaymentQuoteRepositoryInterface;
use Hokodo\BNPL\Model\SaveLog as Logger;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Sales\Model\Order;

/**
 * Class Hokodo\BNPL\Observer\SalesOrderCustomerAssignUserData.
 */
class SalesOrderCustomerAssignUserData implements ObserverInterface
{
    /**
     * @var HokodoOrganisationRepositoryInterface
     */
    private $hokodoOrganisationRepository;

    /**
     * @var PaymentQuoteRepositoryInterface
     */
    private $paymentQuoteRepository;

    /**
     * @var CustomerRepositoryInterface
     */
    private $customerRepository;

    /**
     * @var Logger
     */
    private $logger;

    /**
     * @param HokodoOrganisationRepositoryInterface $hokodoOrganisationRepository
     * @param PaymentQuoteRepositoryInterface       $paymentQuoteRepository
     * @param CustomerRepositoryInterface           $customerRepository
     * @param Logger                                $logger
     */
    public function __construct(
        HokodoOrganisationRepositoryInterface $hokodoOrganisationRepository,
        PaymentQuoteRepositoryInterface $paymentQuoteRepository,
        CustomerRepositoryInterface $customerRepository,
        Logger $logger
    ) {
        $this->hokodoOrganisationRepository = $hokodoOrganisationRepository;
        $this->paymentQuoteRepository = $paymentQuoteRepository;
        $this->customerRepository = $customerRepository;
        $this->logger = $logger;
    }

    /**
     * @inheritDoc
     *
     * @see \Magento\Framework\Event\ObserverInterface::execute()
     */
    public function execute(Observer $observer)
    {
        /**
         * @var Order $order
         */
        $order = $observer->getEvent()->getOrder();

        if ($order->getOrderApiId()) {
            $paymentQuote = $this->getPaymentQuote($order->getOrderApiId(), $order);
            if ($paymentQuote && $paymentQuote->getId()) {
                try {
                    if ($paymentQuote->getUserId() && $paymentQuote->getOrganisationId()) {
                        /**
                         * @var \Magento\Customer\Api\Data\CustomerInterface $customer
                         */
                        $customer = $observer->getEvent()->getCustomer();

                        if ($customer && $customer->getId()) {
                            $customer->setCustomAttribute('hokodo_user_id', $paymentQuote->getUserId());

                            $organisation = $this->hokodoOrganisationRepository->getByApiId(
                                $paymentQuote->getOrganisationId()
                            );

                            $customer->setCustomAttribute('hokodo_organization_id', $organisation->getId());
                            $this->customerRepository->save($customer);
                        }
                    }
                } catch (\Exception $e) {
                    $data = [
                        'payment_log_content' => $e->getMessage(),
                        'action_title' => 'SalesOrderCustomerAssignUserData:: Exception',
                        'status' => 0,
                        'quote_id' => $order->getQuoteId(),
                    ];
                    $this->logger->execute($data);
                    return;
                }
            }
        }
    }

    /**
     * A function that gets payment quotes.
     *
     * @param mixed $orderId
     * @param mixed $order
     *
     * @return PaymentQuoteInterface|null
     *
     * @throws LocalizedException
     */
    private function getPaymentQuote($orderId, $order)
    {
        try {
            return $this->paymentQuoteRepository->getByOrderId($orderId);
        } catch (\Exception $e) {
            $data = [
                'payment_log_content' => $e->getMessage(),
                'action_title' => 'SalesOrderCustomerAssignUserData:: Exception',
                'status' => 0,
                'quote_id' => $order->getQuoteId(),
            ];
            $this->logger->execute($data);
            return null;
        }
    }
}
