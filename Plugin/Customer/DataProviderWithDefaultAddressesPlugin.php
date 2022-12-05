<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Hokodo\BNPL\Plugin\Customer;

use Hokodo\BNPL\Api\HokodoCustomerRepositoryInterface;
use Magento\Customer\Model\Customer\DataProviderWithDefaultAddresses;
use Magento\Framework\UrlInterface;

class DataProviderWithDefaultAddressesPlugin
{
    /**
     * @var HokodoCustomerRepositoryInterface
     */
    private HokodoCustomerRepositoryInterface $hokodoCustomerRepository;

    /**
     * @var UrlInterface
     */
    private UrlInterface $urlBuilder;

    /**
     * @param HokodoCustomerRepositoryInterface $hokodoCustomerRepository
     * @param UrlInterface                      $urlBuilder
     */
    public function __construct(
        HokodoCustomerRepositoryInterface $hokodoCustomerRepository,
        UrlInterface $urlBuilder
    ) {
        $this->hokodoCustomerRepository = $hokodoCustomerRepository;
        $this->urlBuilder = $urlBuilder;
    }

    /**
     * Add Data.
     *
     * @param DataProviderWithDefaultAddresses $subject
     * @param array                            $result
     *
     * @return array
     */
    public function afterGetData(
        DataProviderWithDefaultAddresses $subject,
        array $result
    ): array {
        $hokodoCustomers = [];
        foreach ($result as $id => $entityData) {
            if ($id) {
                $hokodoCustomer = $this->hokodoCustomerRepository->getByCustomerId((int) $id);
                if ($hokodoCustomer->getId()) {
                    $hokodoCustomers[$id]['hokodo']['company_id'] = $hokodoCustomer->getCompanyId();
                }
                $submitUrl = $this->urlBuilder->getUrl('hokodo/customer/savecompanyid');
                $hokodoCustomers[$id]['hokodo']['submit_url'] = $submitUrl;
            }
        }
        return array_replace_recursive($result, $hokodoCustomers);
    }
}
