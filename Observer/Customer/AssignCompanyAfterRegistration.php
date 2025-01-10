<?php

/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Hokodo\BNPL\Observer\Customer;

use Hokodo\BNPL\Api\Data\HokodoCustomerInterfaceFactory;
use Hokodo\BNPL\Api\HokodoCustomerRepositoryInterface;
use Hokodo\BNPL\Api\HokodoEntityTypeResolverInterface;
use Hokodo\BNPL\Api\HokodoQuoteRepositoryInterface;
use Hokodo\BNPL\Model\Config\Source\EntityLevelForSave;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Framework\Api\SearchCriteria;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\OrderRepositoryInterface;

class AssignCompanyAfterRegistration implements ObserverInterface
{
    /**
     * @var HokodoQuoteRepositoryInterface
     */
    private HokodoQuoteRepositoryInterface $hokodoQuoteRepository;

    /**
     * @var SearchCriteriaBuilder
     */
    private SearchCriteriaBuilder $searchCriteriaBuilder;

    /**
     * @var OrderRepositoryInterface
     */
    private OrderRepositoryInterface $orderRepository;

    /**
     * @var HokodoEntityTypeResolverInterface
     */
    private HokodoEntityTypeResolverInterface $hokodoEntityTypeResolver;

    /**
     * @var HokodoCustomerRepositoryInterface
     */
    private HokodoCustomerRepositoryInterface $hokodoCustomerRepository;

    /**
     * @var HokodoCustomerInterfaceFactory
     */
    private HokodoCustomerInterfaceFactory $customerInterfaceFactory;

    /**
     * @param OrderRepositoryInterface          $orderRepository
     * @param HokodoQuoteRepositoryInterface    $hokodoQuoteRepository
     * @param SearchCriteriaBuilder             $searchCriteriaBuilder
     * @param HokodoEntityTypeResolverInterface $hokodoEntityTypeResolver
     * @param HokodoCustomerRepositoryInterface $hokodoCustomerRepository
     * @param HokodoCustomerInterfaceFactory    $customerInterfaceFactory
     */
    public function __construct(
        OrderRepositoryInterface $orderRepository,
        HokodoQuoteRepositoryInterface $hokodoQuoteRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        HokodoEntityTypeResolverInterface $hokodoEntityTypeResolver,
        HokodoCustomerRepositoryInterface $hokodoCustomerRepository,
        HokodoCustomerInterfaceFactory $customerInterfaceFactory
    ) {
        $this->hokodoQuoteRepository = $hokodoQuoteRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->orderRepository = $orderRepository;
        $this->hokodoEntityTypeResolver = $hokodoEntityTypeResolver;
        $this->hokodoCustomerRepository = $hokodoCustomerRepository;
        $this->customerInterfaceFactory = $customerInterfaceFactory;
    }

    /**
     * Observer for customer_register_success.
     *
     * @param Observer $observer
     *
     * @return void
     */
    public function execute(Observer $observer)
    {
        /** @var CustomerInterface $customer */
        $customer = $observer->getEvent()->getData('customer');
        $orders = $this->orderRepository->getList($this->getSearchCriteria($customer->getEmail()))->getItems();
        if (count($orders)) {
            /** @var OrderInterface $order */
            $order = reset($orders);
            $hokodoQuote = $this->hokodoQuoteRepository->getByQuoteId($order->getQuoteId());

            if ($hokodoQuote->getCompanyId() &&
                $this->hokodoEntityTypeResolver->resolve() === EntityLevelForSave::CUSTOMER) {
                $hokodoCustomer = $this->customerInterfaceFactory->create();
                $hokodoCustomer
                    ->setCustomerId((int) $customer->getId())
                    ->setCompanyId($hokodoQuote->getCompanyId())
                    ->setOrganisationId($hokodoQuote->getOrganisationId());

                $this->hokodoCustomerRepository->save($hokodoCustomer);
            }
        }
    }

    /**
     * Get search criteria for order.
     *
     * @param string $customerEmail
     *
     * @return SearchCriteria
     */
    private function getSearchCriteria(string $customerEmail): SearchCriteria
    {
        return $this->searchCriteriaBuilder->addFilter(OrderInterface::CUSTOMER_EMAIL, $customerEmail)->create();
    }
}
