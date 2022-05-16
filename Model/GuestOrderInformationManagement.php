<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Hokodo\BNPL\Model;

use Hokodo\BNPL\Api\Data\HokodoOrganisationInterface;
use Hokodo\BNPL\Api\Data\OrderInformationInterface;
use Hokodo\BNPL\Api\Data\UserInterface;
use Hokodo\BNPL\Api\GuestOrderInformationManagementInterface;
use Hokodo\BNPL\Api\OrderInformationManagementInterface;
use Magento\Quote\Model\QuoteIdMaskFactory;

class GuestOrderInformationManagement implements GuestOrderInformationManagementInterface
{
    /**
     * @var OrderInformationManagementInterface
     */
    private $orderInformationManagement;

    /**
     * @var QuoteIdMaskFactory
     */
    private $quoteIdMaskFactory;

    /**
     * @param OrderInformationManagementInterface $orderInformationManagement
     * @param QuoteIdMaskFactory                  $quoteIdMaskFactory
     */
    public function __construct(
        OrderInformationManagementInterface $orderInformationManagement,
        QuoteIdMaskFactory $quoteIdMaskFactory
    ) {
        $this->orderInformationManagement = $orderInformationManagement;
        $this->quoteIdMaskFactory = $quoteIdMaskFactory;
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\GuestOrderInformationManagementInterface::setOrder()
     */
    public function setOrder($cartId, UserInterface $user, HokodoOrganisationInterface $organisation)
    {
        $quoteIdMask = $this->quoteIdMaskFactory->create()->load($cartId, 'masked_id');

        return $this->orderInformationManagement->setOrder($quoteIdMask->getQuoteId(), $user, $organisation);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\GuestOrderInformationManagementInterface::requestNewOffer()
     */
    public function requestNewOffer($cartId, OrderInformationInterface $order)
    {
        return $this->orderInformationManagement->requestNewOffer($cartId, $order, '');
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\GuestOrderInformationManagementInterface::getPaymentOffer()
     */
    public function getPaymentOffer($offerId, $cartId)
    {
        return $this->orderInformationManagement->getPaymentOffer($offerId, $cartId);
    }
}
