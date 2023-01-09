<?php
/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

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

    /**
     * A function that sets url.
     *
     * @param string $url
     *
     * @return $this
     */
    public function setUrl($url);

    /**
     * A function that gets url.
     *
     * @return string
     */
    public function getUrl();

    /**
     * A function that sets id.
     *
     * @param string $id
     *
     * @return $this
     */
    public function setId($id);

    /**
     * A function that gets id.
     *
     * @return string
     */
    public function getId();

    /**
     * A function that sets order.
     *
     * @param string $order
     *
     * @return $this
     */
    public function setOrder($order);

    /**
     * A function that gets order.
     *
     * @return string
     */
    public function getOrder();

    /**
     * A function that sets offered payment plans.
     *
     * @param \Hokodo\BNPL\Api\Data\PaymentPlanInterface[] $offeredPaymentPlans
     *
     * @return $this
     */
    public function setOfferedPaymentPlans(array $offeredPaymentPlans);

    /**
     * A function that gets offered payment plans.
     *
     * @return \Hokodo\BNPL\Api\Data\PaymentPlanInterface[]
     */
    public function getOfferedPaymentPlans();

    /**
     * A function that sets legals.
     *
     * @param string[] $legals
     *
     * @return $this
     */
    public function setLegals(array $legals);

    /**
     * A function that gets legals.
     *
     * @return string[]
     */
    public function getLegals();

    /**
     * A function that sets urls.
     *
     * @param string[] $urls
     *
     * @return $this
     */
    public function setUrls(array $urls);

    /**
     * A function that gets urls.
     *
     * @return string[]
     */
    public function getUrls();

    /**
     * A function that sets locale.
     *
     * @param string $locale
     *
     * @return $this
     */
    public function setLocale($locale);

    /**
     * A function that gets locale.
     *
     * @return string
     */
    public function getLocale();

    /**
     * A function that sets metadata.
     *
     * @param string $metadata
     *
     * @return $this
     */
    public function setMetadata($metadata);

    /**
     * A function that gets metadata.
     *
     * @return string
     */
    public function getMetadata();
}
