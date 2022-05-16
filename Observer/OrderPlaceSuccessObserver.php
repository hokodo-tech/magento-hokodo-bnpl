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
use Magento\Framework\Event\ObserverInterface;

/**
 * Class Hokodo\BNPL\Observer\OrderPlaceSuccessObserver.
 */
class OrderPlaceSuccessObserver implements ObserverInterface
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
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        /**
         * @var \Magento\Quote\Model\Quote $quote
         */
        $quote = $observer->getEvent()->getQuote();

        $paymentQuote = $this->getPaymentQuote($quote->getId());

        if ($quote->getCustomerId() && $paymentQuote && $paymentQuote->getId()) {
            try {
                if ($paymentQuote->getUserId() && $paymentQuote->getOrganisationId()) {
                    $customer = $this->customerRepository->getById($quote->getCustomerId());
                    $customer->setCustomAttribute('hokodo_user_id', $paymentQuote->getUserId());

                    $organisation = $this->hokodoOrganisationRepository->getByApiId($paymentQuote->getOrganisationId());

                    $customer->setCustomAttribute('hokodo_organization_id', $organisation->getId());
                    $this->customerRepository->save($customer);
                }
            } catch (\Exception $e) {
                $data = [
                    'payment_log_content' => $e->getMessage(),
                    'action_title' => 'OrderPlaceSuccessObserver:: Exception',
                    'status' => 0,
                    'quote_id' => $quote->getId(),
                ];
                $this->logger->execute($data);
                return;
            }
        }
    }

    /**
     * A function that catch exception from payment quote.
     *
     * @param int $quoteId
     *
     * @return PaymentQuoteInterface|null
     */
    private function getPaymentQuote($quoteId)
    {
        try {
            return $this->paymentQuoteRepository->getByQuoteId($quoteId);
        } catch (\Exception $e) {
            $data = [
                'payment_log_content' => $e->getMessage(),
                'action_title' => 'OrderPlaceSuccessObserver:: Exception',
                'status' => 0,
                'quote_id' => $quoteId,
            ];
            $this->logger->execute($data);
            return null;
        }
    }
}
