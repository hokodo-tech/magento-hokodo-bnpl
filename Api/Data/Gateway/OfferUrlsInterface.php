<?php

/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
// @codingStandardsIgnoreFile

declare(strict_types=1);

namespace Hokodo\BNPL\Api\Data\Gateway;

interface OfferUrlsInterface
{
    public const SUCCESS_URL = 'success';
    public const FAILURE_URL = 'failure';
    public const CANCEL_URL = 'cancel';
    public const NOTIFICATION_URL = 'notification';
    public const MERCHANT_TERMS_URL = 'merchant_terms';

    /**
     * Success Url setter.
     *
     * @param string $successUrl
     *
     * @return $this
     */
    public function setSuccessUrl(string $successUrl): self;

    /**
     * Failure Url setter.
     *
     * @param string $failureUrl
     *
     * @return $this
     */
    public function setFailureUrl(string $failureUrl): self;

    /**
     * Cancel Url setter.
     *
     * @param string $cancelUrl
     *
     * @return $this
     */
    public function setCancelUrl(string $cancelUrl): self;

    /**
     * Notification Url setter.
     *
     * @param string $notificationUrl
     *
     * @return $this
     */
    public function setNotificationUrl(string $notificationUrl): self;

    /**
     * Merchant Terms Url setter.
     *
     * @param string $merchantTermsUrl
     *
     * @return $this
     */
    public function setMerchantTermsUrl(string $merchantTermsUrl): self;
}
