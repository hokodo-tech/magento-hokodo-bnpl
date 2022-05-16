<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Api;

/**
 * Interface Hokodo\BNPL\Api\HokodoOrganisationCheckingInterface.
 */
interface HokodoOrganisationCheckingInterface
{
    /**
     * A function that get user is loggedin or not.
     *
     * @return bool
     */
    public function isGuest();

    /**
     * Get current quote by checkout session.
     *
     * @return \Magento\Quote\Model\Quote
     */
    public function getQuote();

    /**
     * Retrieve customer.
     *
     * @param string $emailAddress
     *
     * @return \Magento\Customer\Api\Data\CustomerInterface
     *
     * @throws \Magento\Framework\Exception\NoSuchEntityException if customer with the specified email does not exist
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getCustomerByQuoteEmailAddress($emailAddress);
}
