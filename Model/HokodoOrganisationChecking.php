<?php
/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Hokodo\BNPL\Model;

use Exception;
use Hokodo\BNPL\Api\HokodoOrganisationCheckingInterface;
use Magento\Checkout\Model\SessionFactory as CheckoutSession;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class \Hokodo\BNPL\Model\HokodoOrganisationChecking.
 */
class HokodoOrganisationChecking implements HokodoOrganisationCheckingInterface
{
    /**
     * @var CustomerRepositoryInterface
     */
    protected $customerRepository;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var CheckoutSession
     */
    protected $checkoutSession;

    /**
     * HokodoOrganisationChecking constructor.
     *
     * @param CustomerRepositoryInterface $customerRepository
     * @param StoreManagerInterface       $storeManager
     * @param CheckoutSession             $checkoutSession
     */
    public function __construct(
        CustomerRepositoryInterface $customerRepository,
        StoreManagerInterface $storeManager,
        CheckoutSession $checkoutSession
    ) {
        $this->customerRepository = $customerRepository;
        $this->storeManager = $storeManager;
        $this->checkoutSession = $checkoutSession;
    }

    /**
     * A function that get user is loggedin or not.
     *
     * @return bool
     */
    public function isGuest()
    {
        $quote = $this->getQuote();
        return $quote->getCustomerIsGuest();
    }

    /**
     * Get current quote by checkout session.
     *
     * @return \Magento\Quote\Model\Quote
     */
    public function getQuote()
    {
        return $this->checkoutSession->create()->getQuote();
    }

    /**
     * Retrieve customer.
     *
     * @param string $emailAddress
     *
     * @return CustomerInterface
     *
     * @throws NoSuchEntityException if customer with the specified email does not exist
     * @throws LocalizedException
     */
    public function getCustomerByQuoteEmailAddress($emailAddress)
    {
        $websiteID = $this->storeManager->getStore()->getWebsiteId();
        try {
            $customer = $this->customerRepository->get($emailAddress, $websiteID);
        } catch (NoSuchEntityException $e) {
            $customer = null;
        } catch (Exception $e) {
            throw LocalizedException(__('Unexpected exception: %s', $e->getMessage()), $e);
        }
        return $customer;
    }
}
