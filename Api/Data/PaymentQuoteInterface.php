<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Api\Data;

/**
 * Interface Hokodo\BNPL\Api\Data\PaymentQuoteInterface.
 */
interface PaymentQuoteInterface
{
    public const PAYMENT_QUOTE_ID = 'payment_quote_id';
    public const QUOTE_ID = 'quote_id';
    public const USER_ID = 'user_id';
    public const ORGANISATION_ID = 'organisation_id';
    public const ORDER_ID = 'order_id';
    public const OFFER_ID = 'offer_id';
    public const DEFERRED_PAYMENT_ID = 'deferred_payment_id';

    /**
     * A function that sets payment quote id.
     *
     * @param int $paymentQuoteId
     *
     * @return $this
     */
    public function setPaymentQuoteId($paymentQuoteId);

    /**
     * A function that gets payment quote id.
     *
     * @return int
     */
    public function getPaymentQuoteId();

    /**
     * A function that sets quote id.
     *
     * @param int $quoteId
     *
     * @return $this
     */
    public function setQuoteId($quoteId);

    /**
     * A function that gets quoted id.
     *
     * @return int
     */
    public function getQuoteId();

    /**
     * A function that sets user id.
     *
     * @param string $userId
     *
     * @return $this
     */
    public function setUserId($userId);

    /**
     * A function that gets user id.
     *
     * @return string
     */
    public function getUserId();

    /**
     * A function that sets organisation id.
     *
     * @param string $organisationId
     *
     * @return $this
     */
    public function setOrganisationId($organisationId);

    /**
     * A function that gets organisation id.
     *
     * @return string
     */
    public function getOrganisationId();

    /**
     * A function that sets order id.
     *
     * @param string $orderId
     *
     * @return $this
     */
    public function setOrderId($orderId);

    /**
     * A function that gets order id.
     *
     * @return string
     */
    public function getOrderId();

    /**
     * A function that sets offer id.
     *
     * @param string $offerId
     *
     * @return $this
     */
    public function setOfferId($offerId);

    /**
     * A function that gets offer id.
     *
     * @return string
     */
    public function getOfferId();

    /**
     * A function that sets deferred payment id.
     *
     * @param string $deferredPaymentId
     *
     * @return $this
     */
    public function setDeferredPaymentId($deferredPaymentId);

    /**
     * A function that gets deferred payment id.
     *
     * @return string
     */
    public function getDeferredPaymentId();
}
