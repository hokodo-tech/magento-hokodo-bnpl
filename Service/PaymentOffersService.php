<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Service;

use Hokodo\BNPL\Api\Data\OrderInformationInterface;
use Hokodo\BNPL\Api\Data\PaymentOffersInterface;

/**
 * Class Hokodo\BNPL\Service\PaymentOffersService.
 */
class PaymentOffersService extends AbstractService
{
    /**
     * A function that returns information about order.
     *
     * @param OrderInformationInterface $order
     * @param string                    $cartId
     * @param string                    $customerId
     *
     * @return \Hokodo\BNPL\Api\Data\PaymentOffersInterface
     */
    public function requestNew(OrderInformationInterface $order, $cartId, $customerId = '')
    {
        return $this->executeCommand(
            'payment_offers_request_new',
            [
                'orders' => $order,
                'quote_id' => $cartId,
                'customer_id' => $customerId,
            ]
        )->getDataModel();
    }

    /**
     * A function that returns payment offers view.
     *
     * @param PaymentOffersInterface $paymentOffers
     *
     * @return \Hokodo\BNPL\Api\Data\PaymentOffersInterface
     */
    public function get(PaymentOffersInterface $paymentOffers)
    {
        return $this->executeCommand(
            'payment_offers_view',
            [
                'offers' => $paymentOffers,
            ]
        )->getDataModel();
    }

    /**
     * A function that returns a list of payment offers.
     *
     * @return \Hokodo\BNPL\Api\Data\PaymentOffersInterface[]
     */
    public function getList()
    {
        return $this->executeCommand('payment_offers_list', [])->getList();
    }

    /**
     * A function removes a payment offers.
     *
     * @param PaymentOffersInterface $paymentOffers
     *
     * @return \Hokodo\BNPL\Gateway\Command\Result\Result
     */
    public function delete(PaymentOffersInterface $paymentOffers)
    {
        return $this->executeCommand('payment_offers_delete', [
            'offers' => $paymentOffers,
        ]);
    }
}
