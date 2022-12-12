<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Hokodo\BNPL\CustomerData;

use Hokodo\BNPL\Api\HokodoCustomerRepositoryInterface;
use Magento\Customer\CustomerData\SectionSourceInterface;
use Magento\Customer\Model\Session;
use Psr\Log\LoggerInterface;

class HokodoSearch implements SectionSourceInterface
{
    public const COMPANY_ID = 'companyId';

    /**
     * @var Session
     */
    private Session $customerSession;

    /**
     * @var HokodoCustomerRepositoryInterface
     */
    private HokodoCustomerRepositoryInterface $hokodoCustomerRepository;

    /**
     * @param Session                           $customerSession
     * @param HokodoCustomerRepositoryInterface $hokodoCustomerRepository
     */
    public function __construct(
        Session $customerSession,
        HokodoCustomerRepositoryInterface $hokodoCustomerRepository,
        LoggerInterface $logger
    ) {
        $this->customerSession = $customerSession;
        $this->hokodoCustomerRepository = $hokodoCustomerRepository;
        $this->logger = $logger;
    }

    /**
     * @inheritDoc
     */
    public function getSectionData()
    {
        $hokodoCustomer = $this->hokodoCustomerRepository->getByCustomerId(
            (int) $this->customerSession->getCustomerId()
        );
        $this->logger->alert('WAKE UP!');
        $this->logger->debug('Next step');
        $this->logger->notice('Take a look at this');
        $this->logger->error('Something went wrong', ['param1' => 'value1']);

        return [
            self::COMPANY_ID => $hokodoCustomer->getCompanyId() ?: '',
        ];
    }
}
