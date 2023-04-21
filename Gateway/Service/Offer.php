<?php
/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Hokodo\BNPL\Gateway\Service;

use Hokodo\BNPL\Api\Data\Gateway\CreateOfferRequestInterface;
use Hokodo\BNPL\Gateway\Command\Result\PaymentOfferResultInterface;
use Magento\Framework\Exception\NotFoundException;
use Magento\Payment\Gateway\Command\CommandException;

class Offer extends AbstractService
{
    /**
     * Create offer gateway request.
     *
     * @param CreateOfferRequestInterface $createOfferRequest
     *
     * @return PaymentOfferResultInterface
     *
     * @throws \Magento\Framework\Exception\NotFoundException
     * @throws \Magento\Payment\Gateway\Command\CommandException
     */
    public function createOffer(CreateOfferRequestInterface $createOfferRequest)
    {
        return $this->commandPool->get('sdk_offer_create')->execute($createOfferRequest->__toArray());
    }

    /**
     * Get offer from Hokodo.
     *
     * @param array $getOfferRequest
     *
     * @return PaymentOfferResultInterface
     *
     * @throws CommandException
     * @throws NotFoundException
     */
    public function getOffer(array $getOfferRequest)
    {
        return $this->commandPool->get('sdk_offer_get')->execute($getOfferRequest);
    }
}
