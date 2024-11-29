<?php

/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Hokodo\BNPL\Model\Data\Gateway;

use Hokodo\BNPL\Api\Data\Gateway\CreateOfferRequestInterface;
use Hokodo\BNPL\Api\Data\Gateway\OfferUrlsInterface;
use Magento\Framework\Api\AbstractSimpleObject;

class CreateOfferRequest extends AbstractSimpleObject implements CreateOfferRequestInterface
{
    /**
     * @inheritdoc
     */
    public function setOrder(string $order): self
    {
        $this->setData(self::ORDER, $order);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setUrls(OfferUrlsInterface $urls): self
    {
        $this->setData(self::URLS, $urls);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setLocale(string $locale): self
    {
        $this->setData(self::LOCALE, $locale);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setMetadata(array $metadata): self
    {
        $this->setData(self::METADATA, $metadata);
        return $this;
    }
}
