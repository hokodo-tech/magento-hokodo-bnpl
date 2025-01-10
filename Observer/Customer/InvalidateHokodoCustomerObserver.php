<?php

/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Hokodo\BNPL\Observer\Customer;

use Hokodo\BNPL\Api\HokodoCustomerRepositoryInterface;
use Hokodo\BNPL\Api\HokodoQuoteRepositoryInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Quote\Api\CartRepositoryInterface;
use Psr\Log\LoggerInterface;

/**
 * Observes the `customer_save_after_data_object` event.
 */
class InvalidateHokodoCustomerObserver implements ObserverInterface
{
    /**
     * @var HokodoCustomerRepositoryInterface
     */
    private HokodoCustomerRepositoryInterface $hokodoCustomerRepository;

    /**
     * @var HokodoQuoteRepositoryInterface
     */
    private HokodoQuoteRepositoryInterface $hokodoQuoteRepository;

    /**
     * @var CartRepositoryInterface
     */
    private CartRepositoryInterface $cartRepository;

    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * @param HokodoCustomerRepositoryInterface $hokodoCustomerRepository
     * @param HokodoQuoteRepositoryInterface    $hokodoQuoteRepository
     * @param CartRepositoryInterface           $cartRepository
     * @param LoggerInterface                   $logger
     */
    public function __construct(
        HokodoCustomerRepositoryInterface $hokodoCustomerRepository,
        HokodoQuoteRepositoryInterface $hokodoQuoteRepository,
        CartRepositoryInterface $cartRepository,
        LoggerInterface $logger
    ) {
        $this->hokodoCustomerRepository = $hokodoCustomerRepository;
        $this->hokodoQuoteRepository = $hokodoQuoteRepository;
        $this->cartRepository = $cartRepository;
        $this->logger = $logger;
    }

    /**
     * Observer for customer_save_after_data_object.
     *
     * @param Observer $observer
     *
     * @return void
     */
    public function execute(Observer $observer)
    {
        $originalCustomer = $observer->getEvent()->getOrigCustomerDataObject();
        if (!$originalCustomer) {
            return;
        }

        $customer = $observer->getEvent()->getCustomerDataObject();
        if ($customer->getEmail() !== $originalCustomer->getEmail()) {
            $hokodoCustomer = $this->hokodoCustomerRepository->getByCustomerId((int) $customer->getId());
            if ($hokodoCustomer->getUserId()) {
                $hokodoCustomer->setUserId('');
                $this->hokodoCustomerRepository->save($hokodoCustomer);
                try {
                    $this->hokodoQuoteRepository->deleteByCustomerId((int) $customer->getId());
                } catch (\Exception $e) {
                    $data = [
                        'message' => 'Hokodo_BNPL: There was an error during quote deletion.',
                        'error' => $e->getMessage(),
                    ];
                    $this->logger->debug(__METHOD__, $data);
                }
            }
        }
    }
}
