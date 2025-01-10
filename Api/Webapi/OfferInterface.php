<?php

/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Hokodo\BNPL\Api\Webapi;

use Hokodo\BNPL\Api\Data\Webapi\OfferRequestInterface;
use Hokodo\BNPL\Api\Data\Webapi\OfferResponseInterface;

interface OfferInterface
{
    /**
     * Create order request webapi handler.
     *
     * @param OfferRequestInterface $payload
     *
     * @return OfferResponseInterface
     */
    public function requestNew(OfferRequestInterface $payload): OfferResponseInterface;

    /**
     * Request new offer method for guest user.
     *
     * @param OfferRequestInterface $payload
     *
     * @return OfferResponseInterface
     */
    public function guestRequestNew(OfferRequestInterface $payload): OfferResponseInterface;
}
