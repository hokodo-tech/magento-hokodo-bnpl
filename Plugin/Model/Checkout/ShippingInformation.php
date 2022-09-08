<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Hokodo\BNPL\Plugin\Model\Checkout;

use Hokodo\BNPL\Api\Data\HokodoQuoteInterface;
use Hokodo\BNPL\Api\HokodoQuoteRepositoryInterface;
use Magento\Checkout\Api\Data\ShippingInformationInterface;
use Magento\Checkout\Model\Session;
use Magento\Quote\Api\Data\AddressInterface;

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
     * @param ShippingInformationInterface                          $addressInformation
     *
     * @return void
     *
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function beforeSaveAddressInformation(
        \Magento\Checkout\Model\ShippingInformationManagement $subject,
        $cartId,
        ShippingInformationInterface $addressInformation
    ) {
        if ($this->isAddressChanged($addressInformation->getShippingAddress()) ||
            $this->checkoutSession->getQuote()->getShippingAddress()->getShippingMethod() !==
            $addressInformation->getShippingMethodCode() . '_' . $addressInformation->getShippingCarrierCode()
        ) {
            $hokodoQuote = $this->hokodoQuoteRepository->getByQuoteId($cartId);
            if ($hokodoQuote->getOrderId()) {
                $hokodoQuote->setOfferId('')->setPatchRequired(HokodoQuoteInterface::PATCH_ADDRESS);
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
            || $this->isAddressesFieldsChanged($addressToSave->getData(), $addressOriginal->getData());
    }

    /**
     * Checks if address changed comparing fields.
     *
     * @param array $fieldsToSave
     * @param array $fieldsOriginal
     *
     * @return bool
     */
    private function isAddressesFieldsChanged(array $fieldsToSave, array $fieldsOriginal): bool
    {
        foreach ($fieldsToSave as $fieldName => $value) {
            if (isset($fieldsOriginal[$fieldName]) && $fieldsOriginal[$fieldName] != $value) {
                return true;
            }
        }
        return false;
    }
}
