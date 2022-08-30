<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
// @codingStandardsIgnoreFile

declare(strict_types=1);

namespace Hokodo\BNPL\Api\Data\Webapi;

interface OfferRequestInterface
{
    public const ORGANISATION_ID = 'organisation_id';
    public const USER_ID = 'user_id';
    public const QUOTE_ID = 'quote_id';

    /**
     * Organisation Id getter.
     *
     * @return string
     */
    public function getOrganisationId(): string;

    /**
     * Organisation Id setter.
     *
     * @param string $organisationId
     *
     * @return $this
     */
    public function setOrganisationId(string $organisationId): self;

    /**
     * User Id getter.
     *
     * @return string
     */
    public function getUserId(): string;

    /**
     * User Id setter.
     *
     * @param string $userId
     *
     * @return $this
     */
    public function setUserId(string $userId): self;

    /**
     * Quote Id getter.
     *
     * @return string
     */
    public function getQuoteId(): string;

    /**
     * Quote Id setter.
     *
     * @param string $quoteId
     *
     * @return $this
     */
    public function setQuoteId(string $quoteId): self;
}
