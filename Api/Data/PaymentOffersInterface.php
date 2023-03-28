<?php
/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Hokodo\BNPL\Api\Data;

use Magento\Framework\Api\ExtensibleDataInterface;

interface PaymentOffersInterface extends ExtensibleDataInterface
{
    public const URL = 'url';
    public const ID = 'id';
    public const ORDER = 'order';
    public const OFFERED_PAYMENT_PLANS = 'offered_payment_plans';
    public const LEGALS = 'legals';
    public const URLS = 'urls';
    public const LOCALE = 'locale';
    public const METADATA = 'metadata';
    public const IS_ELIGIBLE = 'is_eligible';

    /**
     * A function that sets url.
     *
     * @param string|null $url
     *
     * @return $this
     */
    public function setUrl(string $url = null): self;

    /**
     * A function that gets url.
     *
     * @return string
     */
    public function getUrl(): ?string;

    /**
     * A function that sets id.
     *
     * @param string|null $id
     *
     * @return $this
     */
    public function setId(string $id = null): self;

    /**
     * A function that gets id.
     *
     * @return string
     */
    public function getId(): ?string;

    /**
     * A function that sets order.
     *
     * @param string|null $order
     *
     * @return $this
     */
    public function setOrder(string $order = null): self;

    /**
     * A function that gets order.
     *
     * @return string
     */
    public function getOrder(): ?string;

    /**
     * A function that sets offered payment plans.
     *
     * @param \Hokodo\BNPL\Api\Data\PaymentPlanInterface[] $offeredPaymentPlans
     *
     * @return $this
     */
    public function setOfferedPaymentPlans(array $offeredPaymentPlans = []): self;

    /**
     * A function that gets offered payment plans.
     *
     * @return \Hokodo\BNPL\Api\Data\PaymentPlanInterface[]
     */
    public function getOfferedPaymentPlans(): array;

    /**
     * A function that sets legals.
     *
     * @param string[] $legals
     *
     * @return $this
     */
    public function setLegals(array $legals = []): self;

    /**
     * A function that gets legals.
     *
     * @return string[]
     */
    public function getLegals(): array;

    /**
     * A function that sets urls.
     *
     * @param string[] $urls
     *
     * @return $this
     */
    public function setUrls(array $urls = []): self;

    /**
     * A function that gets urls.
     *
     * @return string[]
     */
    public function getUrls(): array;

    /**
     * A function that sets locale.
     *
     * @param string|null $locale
     *
     * @return $this
     */
    public function setLocale(string $locale = null): self;

    /**
     * A function that gets locale.
     *
     * @return string
     */
    public function getLocale(): ?string;

    /**
     * A function that sets metadata.
     *
     * @param string[] $metadata
     *
     * @return $this
     */
    public function setMetadata(array $metadata = []): self;

    /**
     * A function that gets metadata.
     *
     * @return string[]
     */
    public function getMetadata(): array;

    /**
     * A function that sets is_eligible flag.
     *
     * @param bool|null $isEligible
     *
     * @return $this
     */
    public function setIsEligible(bool $isEligible = null): self;

    /**
     * A function that gets is_eligible flag.
     *
     * @return bool
     */
    public function getIsEligible(): bool;
}
