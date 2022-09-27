<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
// @codingStandardsIgnoreFile

declare(strict_types=1);

namespace Hokodo\BNPL\Api\Data\Gateway;

interface CreateOfferRequestInterface
{
    public const ORDER = 'order';
    public const URLS = 'urls';
    public const LOCALE = 'locale';
    public const METADATA = 'metadata';

    /**
     * Order setter.
     *
     * @param string $order
     *
     * @return $this
     */
    public function setOrder(string $order): self;

    /**
     * Urls setter.
     *
     * @param OfferUrlsInterface $urls
     *
     * @return $this
     */
    public function setUrls(OfferUrlsInterface $urls): self;

    /**
     * Locale setter.
     *
     * @param string $locale
     *
     * @return $this
     */
    public function setLocale(string $locale): self;

    /**
     * Metadata setter.
     *
     * @param array $metadata
     *
     * @return $this
     */
    public function setMetadata(array $metadata): self;
}
