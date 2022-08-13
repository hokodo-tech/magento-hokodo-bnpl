<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Hokodo\BNPL\Gateway\Service;

use Hokodo\BNPL\Api\Data\Gateway\CreateOfferRequestInterface;
use Magento\Payment\Gateway\Command\ResultInterface;

class Offer extends AbstractService
{
    /**
     * Create offer gateway request
     *
     * @param CreateOfferRequestInterface $createOfferRequest
     *
     * @return ResultInterface
     *
     * @throws \Magento\Framework\Exception\NotFoundException
     * @throws \Magento\Payment\Gateway\Command\CommandException
     */
    public function createOffer(CreateOfferRequestInterface $createOfferRequest): ResultInterface
    {
        return $this->commandPool->get('sdk_offer_create')->execute($createOfferRequest->__toArray());
    }
}
