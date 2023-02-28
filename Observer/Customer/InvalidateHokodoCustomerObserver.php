<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Hokodo\BNPL\Observer\Customer;

use Hokodo\BNPL\Api\HokodoCustomerRepositoryInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

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
     * @param HokodoCustomerRepositoryInterface $hokodoCustomerRepository
     */
    public function __construct(
        HokodoCustomerRepositoryInterface $hokodoCustomerRepository
    ) {
        $this->hokodoCustomerRepository = $hokodoCustomerRepository;
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
            }
        }
    }
}
