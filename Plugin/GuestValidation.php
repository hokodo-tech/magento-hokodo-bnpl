<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Hokodo\BNPL\Plugin;

use Hokodo\BNPL\Gateway\Config\Config;
use Magento\Checkout\Api\GuestPaymentInformationManagementInterface;
use Magento\CheckoutAgreements\Api\CheckoutAgreementsListInterface;
use Magento\CheckoutAgreements\Model\AgreementsProvider;
use Magento\CheckoutAgreements\Model\Api\SearchCriteria\ActiveStoreAgreementsFilter;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Quote\Api\Data\AddressInterface;
use Magento\Quote\Api\Data\PaymentInterface;
use Magento\Store\Model\ScopeInterface;

class GuestValidation
{
    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfiguration;

    /**
     * @var CheckoutAgreementsListInterface
     */
    private $checkoutAgreementsList;

    /**
     * @var ActiveStoreAgreementsFilter
     */
    private $activeStoreAgreementsFilter;

    /**
     * @var AgreementsProvider
     */
    private $agreementsProvider;

    /**
     * A construct.
     *
     * @param ScopeConfigInterface            $scopeConfiguration
     * @param CheckoutAgreementsListInterface $checkoutAgreementsList
     * @param ActiveStoreAgreementsFilter     $activeStoreAgreementsFilter
     * @param AgreementsProvider              $agreementsProvider
     */
    public function __construct(
        ScopeConfigInterface $scopeConfiguration,
        CheckoutAgreementsListInterface $checkoutAgreementsList,
        ActiveStoreAgreementsFilter $activeStoreAgreementsFilter,
        AgreementsProvider $agreementsProvider
    ) {
        $this->scopeConfiguration = $scopeConfiguration;
        $this->checkoutAgreementsList = $checkoutAgreementsList;
        $this->activeStoreAgreementsFilter = $activeStoreAgreementsFilter;
        $this->agreementsProvider = $agreementsProvider;
    }

    /**
     * A function that saved payment information and place order.
     *
     * @param GuestPaymentInformationManagementInterface $subject
     * @param string                                     $cartId
     * @param string                                     $email
     * @param PaymentInterface                           $paymentMethod
     * @param AddressInterface|null                      $billingAddress
     *
     * @return void
     */
    public function beforeSavePaymentInformationAndPlaceOrder(
        GuestPaymentInformationManagementInterface $subject,
        $cartId,
        $email,
        PaymentInterface $paymentMethod,
        AddressInterface $billingAddress = null
    ) {
        if ($paymentMethod->getMethod() !== Config::CODE) {
            return;
        }
        if ($this->isAgreementEnabled()) {
            $this->validateAgreements($paymentMethod);
        }
    }

    /**
     * Validates agreements.
     *
     * @param PaymentInterface $paymentMethod
     *
     * @throws CouldNotSaveException
     *
     * @return void
     */
    private function validateAgreements(PaymentInterface $paymentMethod)
    {
        $requiredAgreements = $this->agreementsProvider->getRequiredAgreementIds();
        $paymentMethod->getExtensionAttributes()->setAgreementIds($requiredAgreements);
        $paymentMethod->save();
    }

    /**
     * Verify if agreement validation needed.
     *
     * @return bool
     */
    private function isAgreementEnabled()
    {
        $isAgreementsEnabled = $this->scopeConfiguration->isSetFlag(
            AgreementsProvider::PATH_ENABLED,
            ScopeInterface::SCOPE_STORE
        );
        $agreementsList = $isAgreementsEnabled
            ? $this->checkoutAgreementsList->getList($this->activeStoreAgreementsFilter->buildSearchCriteria())
            : [];
        return (bool) ($isAgreementsEnabled && count($agreementsList) > 0);
    }
}
