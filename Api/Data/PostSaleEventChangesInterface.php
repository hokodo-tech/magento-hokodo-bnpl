<?php

/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Hokodo\BNPL\Api\Data;

use Magento\Framework\Api\ExtensibleDataInterface;

interface PostSaleEventChangesInterface extends ExtensibleDataInterface
{
    public const AUTHORISATION = 'authorisation';
    public const PROTECTED_CAPTURES = 'protected_captures';
    public const UNPROTECTED_CAPTURES = 'unprotected_captures';
    public const REFUNDS = 'refunds';
    public const VOIDED_AUTHORISATION = 'voided_authorisation';
    public const EXPIRED_AUTHORISATION = 'expired_authorisation';

    /**
     * Authorisation getter.
     *
     * @return int
     */
    public function getAuthorisation(): int;

    /**
     * Authorisation setter.
     *
     * @param int $authorisation
     *
     * @return $this
     */
    public function setAuthorisation(int $authorisation): self;

    /**
     * Protected Captures getter.
     *
     * @return int
     */
    public function getProtectedCaptures(): int;

    /**
     * Protected Captures setter.
     *
     * @param int $protectedCaptures
     *
     * @return $this
     */
    public function setProtectedCaptures(int $protectedCaptures): self;

    /**
     * Unprotected Captures getter.
     *
     * @return int
     */
    public function getUnprotectedCaptures(): int;

    /**
     * Unprotected Captures setter.
     *
     * @param int $unprotectedCaptures
     *
     * @return $this
     */
    public function setUnprotectedCaptures(int $unprotectedCaptures): self;

    /**
     * Refunds getter.
     *
     * @return int
     */
    public function getRefunds(): int;

    /**
     * Refunds setter.
     *
     * @param int $refunds
     *
     * @return $this
     */
    public function setRefunds(int $refunds): self;

    /**
     * Voided Authorisation getter.
     *
     * @return int
     */
    public function getVoidedAuthorisation(): int;

    /**
     * Voided Authorisation setter.
     *
     * @param int $voidedAuthorisation
     *
     * @return $this
     */
    public function setVoidedAuthorisation(int $voidedAuthorisation): self;

    /**
     * Expired Authorisation getter.
     *
     * @return int
     */
    public function getExpiredAuthorisation(): int;

    /**
     * Expired Authorisation setter.
     *
     * @param int $expiredAuthorisation
     *
     * @return $this
     */
    public function setExpiredAuthorisation(int $expiredAuthorisation): self;
}
