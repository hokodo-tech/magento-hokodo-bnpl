<?php

/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Hokodo\BNPL\Model\Data\Gateway;

use Hokodo\BNPL\Api\Data\Gateway\OfferUrlsInterface;
use Magento\Framework\Api\AbstractSimpleObject;

class OfferUrls extends AbstractSimpleObject implements OfferUrlsInterface
{
    /**
     * @inheritdoc
     */
    public function setSuccessUrl(string $successUrl): self
    {
        $this->setData(self::SUCCESS_URL, $successUrl);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setCancelUrl(string $cancelUrl): self
    {
        $this->setData(self::CANCEL_URL, $cancelUrl);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setFailureUrl(string $failureUrl): self
    {
        $this->setData(self::FAILURE_URL, $failureUrl);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setNotificationUrl(string $notificationUrl): self
    {
        $this->setData(self::NOTIFICATION_URL, $notificationUrl);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setMerchantTermsUrl(string $merchantTermsUrl): self
    {
        $this->setData(self::MERCHANT_TERMS_URL, $merchantTermsUrl);
        return $this;
    }
}
