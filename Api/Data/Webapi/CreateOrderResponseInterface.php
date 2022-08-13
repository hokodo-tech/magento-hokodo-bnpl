<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
// @codingStandardsIgnoreFile

declare(strict_types=1);

namespace Hokodo\BNPL\Api\Data\Webapi;

use Hokodo\BNPL\Api\Data\PaymentOffersInterface;

interface CreateOrderResponseInterface
{
    public const ORDER_ID = 'id';
    public const OFFER = 'offer';

    /**
     * Id getter.
     *
     * @return string
     */
    public function getId(): string;

    /**
     * Id setter.
     *
     * @param string $id
     *
     * @return $this
     */
    public function setId(string $id): self;

    /**
     * Offer getter.
     *
     * @return \Hokodo\BNPL\Api\Data\PaymentOffersInterface|null
     */
    public function getOffer(): ?PaymentOffersInterface;

    /**
     * Offer setter.
     *
     * @param \Hokodo\BNPL\Api\Data\PaymentOffersInterface|null $offer
     *
     * @return $this
     */
    public function setOffer(?PaymentOffersInterface $offer): self;
}
