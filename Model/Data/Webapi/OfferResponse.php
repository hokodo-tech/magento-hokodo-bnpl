<?php

/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Hokodo\BNPL\Model\Data\Webapi;

use Hokodo\BNPL\Api\Data\PaymentOffersInterface;
use Hokodo\BNPL\Api\Data\Webapi\OfferResponseInterface;
use Magento\Framework\Api\AbstractSimpleObject;

class OfferResponse extends AbstractSimpleObject implements OfferResponseInterface
{
    /**
     * @inheritdoc
     */
    public function getOffer(): ?PaymentOffersInterface
    {
        return $this->_get(self::OFFER);
    }

    /**
     * @inheritdoc
     */
    public function setOffer(?PaymentOffersInterface $offer): self
    {
        $this->setData(self::OFFER, $offer);
        return $this;
    }
}
