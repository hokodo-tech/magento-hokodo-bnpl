<?php
/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Hokodo\BNPL\ViewModel\Checkout;

use Magento\Checkout\Model\Session;
use Magento\Customer\CustomerData\Customer as CustomerData;
use Magento\Framework\View\Element\Block\ArgumentInterface;

class OnepageSuccess implements ArgumentInterface
{
    /**
     * @var Session
     */
    private Session $session;

    /**
     * @var CustomerData
     */
    private CustomerData $customerData;

    /**
     * OnepageSuccess constructor.
     *
     * @param Session      $session
     * @param CustomerData $customerData
     */
    public function __construct(
        Session $session,
        CustomerData $customerData
    ) {
        $this->session = $session;
        $this->customerData = $customerData;
    }

    /**
     * Return payment method code.
     *
     * @return string
     */
    public function getSelectedPaymentMethodCode()
    {
        return $this->getOrder()->getPayment()->getMethod();
    }

    /**
     * Return last real order.
     *
     * @return \Magento\Sales\Model\Order
     */
    public function getOrder()
    {
        return $this->session->getLastRealOrder();
    }

    /**
     * Check can push Analytics or not.
     *
     * @return bool
     */
    public function canPushAnalytics(): bool
    {
        $canPush = true;
        $customerSectionData = $this->customerData->getSectionData();
        if (isset($customerSectionData['canPushAnalytics'])) {
            $canPush = $customerSectionData['canPushAnalytics'];
        }
        return $canPush;
    }
}
