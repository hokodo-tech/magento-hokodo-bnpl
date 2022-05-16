<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Hokodo\BNPL\Plugin;

use Hokodo\BNPL\Gateway\Config\Config;
use Magento\Checkout\Api\PaymentInformationManagementInterface;
use Magento\CheckoutAgreements\Api\CheckoutAgreementsListInterface;
use Magento\CheckoutAgreements\Model\AgreementsProvider;
use Magento\CheckoutAgreements\Model\Api\SearchCriteria\ActiveStoreAgreementsFilter;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Quote\Api\Data\AddressInterface;
use Magento\Quote\Api\Data\PaymentInterface;
use Magento\Store\Model\ScopeInterface;

class Validation
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
     * Validates agreements before save payment information and  order placing.
     *
     * @param PaymentInformationManagementInterface $subject
     * @param int                                   $cartId
     * @param PaymentInterface                      $paymentMethod
     * @param AddressInterface|null                 $billingAddress
     *
     * @throws CouldNotSaveException
     *
     * @return void
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function beforeSavePaymentInformationAndPlaceOrder(
        PaymentInformationManagementInterface $subject,
        $cartId,
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
     * Validate agreements base on the payment method.
     *
     * @param PaymentInterface $paymentMethod
     *
     * @throws CouldNotSaveException
     *
     * @return void
     */
    protected function validateAgreements(PaymentInterface $paymentMethod)
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
