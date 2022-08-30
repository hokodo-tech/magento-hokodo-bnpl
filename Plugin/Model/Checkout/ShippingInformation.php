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
        if ($addressInformation->getShippingAddress()->getCustomerAddressId()
            != $this->checkoutSession->getQuote()->getShippingAddress()->getCustomerAddressId()) {
            $hokodoQuote = $this->hokodoQuoteRepository->getByQuoteId($cartId);
            if ($hokodoQuote->getOrderId()) {
                $hokodoQuote->setOfferId('')->setPatchRequired(HokodoQuoteInterface::PATCH_ADDRESS);
                $this->hokodoQuoteRepository->save($hokodoQuote);
            }
        }
    }
}
