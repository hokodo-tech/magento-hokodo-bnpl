<?php

/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Hokodo\BNPL\CustomerData;

use Hokodo\BNPL\Model\HokodoCompanyProvider;
use Magento\Customer\CustomerData\SectionSourceInterface;
use Magento\Customer\Model\Session;

class HokodoSearch implements SectionSourceInterface
{
    public const COMPANY_ID = 'companyId';

    /**
     * @var Session
     */
    private Session $customerSession;

    /**
     * @var HokodoCompanyProvider
     */
    private HokodoCompanyProvider $hokodoCompanyProvider;

    /**
     * @param Session               $customerSession
     * @param HokodoCompanyProvider $hokodoCompanyProvider
     */
    public function __construct(
        Session $customerSession,
        HokodoCompanyProvider $hokodoCompanyProvider
    ) {
        $this->customerSession = $customerSession;
        $this->hokodoCompanyProvider = $hokodoCompanyProvider;
    }

    /**
     * @inheritDoc
     */
    public function getSectionData()
    {
        $companyId = '';
        if ($customerId = $this->customerSession->getCustomerId()) {
            $hokodoEntity = $this->hokodoCompanyProvider->getEntityRepository()->getByCustomerId((int) $customerId);
            $companyId = $hokodoEntity->getCompanyId() ?: '';
        }

        return [
            self::COMPANY_ID => $companyId,
        ];
    }
}
