<?php

/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Hokodo\BNPL\Plugin\Checkout\Model;

use Hokodo\BNPL\Api\Data\HokodoQuoteInterface;
use Hokodo\BNPL\Api\HokodoQuoteRepositoryInterface;
use Magento\Checkout\Api\Data\ShippingInformationInterface;
use Magento\Checkout\Model\Session;
use Magento\Quote\Api\Data\AddressInterface;
use Magento\Sales\Api\Data\OrderAddressInterface;

class ShippingInformation
{
    /**
     * @var HokodoQuoteRepositoryInterface
     */
    private HokodoQuoteRepositoryInterface $hokodoQuoteRepository;

    /**
     * @var Session
     */
    private Session $checkoutSession;

    /**
     * @param HokodoQuoteRepositoryInterface $hokodoQuoteRepository
     * @param Session                        $checkoutSession
     */
    public function __construct(
        HokodoQuoteRepositoryInterface $hokodoQuoteRepository,
        Session $checkoutSession
    ) {
        $this->hokodoQuoteRepository = $hokodoQuoteRepository;
        $this->checkoutSession = $checkoutSession;
    }

    /**
     * Checks if registered user changed the address.
     *
     * @param \Magento\Checkout\Model\ShippingInformationManagement $subject
     * @param string                                                $cartId
     * @param ShippingInformationInterface                          $shippingInformation
     *
     * @return void
     *
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function beforeSaveAddressInformation(
        \Magento\Checkout\Model\ShippingInformationManagement $subject,
        $cartId,
        ShippingInformationInterface $shippingInformation
    ) {
        $isShippingChanged = $this->isShippingChanged($shippingInformation);
        $isAddressChanged = $this->isAddressChanged($shippingInformation->getShippingAddress());
        $isUserChanged = $this->isUserChanged($shippingInformation);
        if ($isShippingChanged || $isAddressChanged || $isUserChanged) {
            $hokodoQuote = $this->hokodoQuoteRepository->getByQuoteId($cartId);
            if ($hokodoQuote->getOrderId()) {
                $hokodoQuote->setOfferId('');
                if ($isShippingChanged) {
                    $hokodoQuote->setPatchType(HokodoQuoteInterface::PATCH_SHIPPING);
                }
                if ($isAddressChanged) {
                    $hokodoQuote->setPatchType(HokodoQuoteInterface::PATCH_ADDRESS);
                }
                if ($isUserChanged) {
                    $hokodoQuote
                        ->setOfferId('')
                        ->setOrderId('')
                        ->setUserId('')
                        ->setOrganisationId('')
                        ->setPatchType(null);
                }
                $this->hokodoQuoteRepository->save($hokodoQuote);
            }
        }
    }

    /**
     * Checks if Address was changed.
     *
     * @param AddressInterface $addressToSave
     *
     * @return bool
     *
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    private function isAddressChanged(AddressInterface $addressToSave): bool
    {
        $addressOriginal = $this->checkoutSession->getQuote()->getShippingAddress();
        return (int) $addressToSave->getCustomerAddressId() !== (int) $addressOriginal->getCustomerAddressId()
            || $this->isOneOfTheFieldsChanged($addressToSave->getData(), $addressOriginal->getData());
    }

    /**
     * Checks if provided fields changed.
     *
     * @param array $fieldsToSave
     * @param array $fieldsOriginal
     *
     * @return bool
     */
    private function isOneOfTheFieldsChanged(array $fieldsToSave, array $fieldsOriginal): bool
    {
        foreach ($fieldsToSave as $fieldName => $value) {
            if (isset($fieldsOriginal[$fieldName]) && $fieldsOriginal[$fieldName] != $value) {
                return true;
            }
        }
        return false;
    }

    /**
     * Checks if shipping method was changed.
     *
     * @param ShippingInformationInterface $shippingInformation
     *
     * @return bool
     *
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    private function isShippingChanged(ShippingInformationInterface $shippingInformation): bool
    {
        return $this->checkoutSession->getQuote()->getShippingAddress()->getShippingMethod() !==
            $shippingInformation->getShippingMethodCode() . '_' . $shippingInformation->getShippingCarrierCode();
    }

    /**
     * Checks if guest user data was changed during checkout.
     *
     * @param ShippingInformationInterface $shippingInformation
     *
     * @return bool
     *
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    private function isUserChanged(ShippingInformationInterface $shippingInformation): bool
    {
        return (bool) $this->checkoutSession->getQuote()->getCustomerIsGuest() && $this->isOneOfTheFieldsChanged(
            $this->getGuestCustomerData($shippingInformation),
            $this->checkoutSession->getQuote()->getBillingAddress()->getData()
        );
    }

    /**
     * Combine guest user data in one array.
     *
     * @param ShippingInformationInterface $shippingInformation
     *
     * @return array
     */
    private function getGuestCustomerData(ShippingInformationInterface $shippingInformation): array
    {
        $billingAddress = $shippingInformation->getBillingAddress();
        return [
            OrderAddressInterface::EMAIL => $shippingInformation->getExtensionAttributes()->getEmail(),
            OrderAddressInterface::FIRSTNAME => $billingAddress->getFirstname(),
            OrderAddressInterface::MIDDLENAME => $billingAddress->getMiddlename(),
            OrderAddressInterface::LASTNAME => $billingAddress->getLastname(),
        ];
    }
}
