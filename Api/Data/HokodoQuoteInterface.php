<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Hokodo\BNPL\Api\Data;

interface HokodoQuoteInterface
{
    public const ID = 'id';
    public const QUOTE_ID = 'quote_id';
    public const USER_ID = 'user_id';
    public const ORGANISATION_ID = 'organisation_id';
    public const ORDER_ID = 'order_id';
    public const OFFER_ID = 'offer_id';

    /**
     * Quote Id getter.
     *
     * @return int|null
     */
    public function getQuoteId(): ?int;

    /**
     * Quote Id setter.
     *
     * @param int $quoteId
     *
     * @return $this
     */
    public function setQuoteId(int $quoteId): self;

    /**
     * User Id getter.
     *
     * @return string|null
     */
    public function getUserId(): ?string;

    /**
     * User Id setter.
     *
     * @param string $userId
     *
     * @return $this
     */
    public function setUserId(string $userId): self;

    /**
     * Organisation Id getter.
     *
     * @return string|null
     */
    public function getOrganisationId(): ?string;

    /**
     * Organisation Id setter.
     *
     * @param string $organisationId
     *
     * @return $this
     */
    public function setOrganisationId(string $organisationId): self;

    /**
     * Order Id getter.
     *
     * @return string|null
     */
    public function getOrderId(): ?string;

    /**
     * Order Id setter.
     *
     * @param string $orderId
     *
     * @return $this
     */
    public function setOrderId(string $orderId): self;

    /**
     * Offer Id getter.
     *
     * @return string|null
     */
    public function getOfferId(): ?string;

    /**
     * Offer Id setter.
     *
     * @param string $offerId
     *
     * @return $this
     */
    public function setOfferId(string $offerId): self;
}
