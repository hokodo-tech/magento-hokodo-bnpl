<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
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
     * @param \Hokodo\BNPL\Api\Data\Webapi\OfferRequestInterface $payload
     *
     * @return \Hokodo\BNPL\Api\Data\Webapi\OfferResponseInterface
     */
    public function requestNew(OfferRequestInterface $payload): OfferResponseInterface;

    /**
     * Request new offer method for guest user.
     *
     * @param \Hokodo\BNPL\Api\Data\Webapi\OfferRequestInterface $payload
     *
     * @return \Hokodo\BNPL\Api\Data\Webapi\OfferResponseInterface
     */
    public function guestRequestNew(OfferRequestInterface $payload): OfferResponseInterface;
}
