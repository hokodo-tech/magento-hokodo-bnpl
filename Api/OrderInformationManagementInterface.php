<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Api;

/**
 * Interface Hokodo\BNPL\Api\OrderInformationManagementInterface.
 */
interface OrderInformationManagementInterface
{
    /**
     * A function that set order.
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
     * A function that request new offer.
     *
     * @param int                                             $cartId
     * @param \Hokodo\BNPL\Api\Data\OrderInformationInterface $order
     * @param int                                             $customerId
     *
     * @return \Hokodo\BNPL\Api\Data\PaymentOffersInterface
     */
    public function requestNewOffer(
        $cartId,
        Data\OrderInformationInterface $order,
        $customerId
    );

    /**
     * A function that gets payment offer.
     *
     * @param string $offerId
     * @param int    $cartId
     *
     * @return \Hokodo\BNPL\Api\Data\PaymentOffersInterface
     */
    public function getPaymentOffer($offerId, $cartId);
}
