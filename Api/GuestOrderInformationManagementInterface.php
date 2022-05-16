<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Api;

/**
 * Interface Hokodo\BNPL\Api\GuestOrderInformationManagementInterface.
 */
interface GuestOrderInformationManagementInterface
{
    /**
     * A function that sets order.
     *
     * @param string                                            $cartId
     * @param \Hokodo\BNPL\Api\Data\UserInterface               $user
     * @param \Hokodo\BNPL\Api\Data\HokodoOrganisationInterface $organisation
     *
     * @return \Hokodo\BNPL\Api\Data\OrderInformationInterface
     */
    public function setOrder(
        $cartId,
        Data\UserInterface $user,
        Data\HokodoOrganisationInterface $organisation
    );

    /**
     * A function that requests new offer.
     *
     * @param string                                          $cartId
     * @param \Hokodo\BNPL\Api\Data\OrderInformationInterface $order
     *
     * @return \Hokodo\BNPL\Api\Data\PaymentOffersInterface
     */
    public function requestNewOffer(
        $cartId,
        Data\OrderInformationInterface $order
    );

    /**
     * A function that gets payment offer.
     *
     * @param string $offerId
     * @param string $cartId
     *
     * @return \Hokodo\BNPL\Api\Data\PaymentOffersInterface
     */
    public function getPaymentOffer($offerId, $cartId);
}
