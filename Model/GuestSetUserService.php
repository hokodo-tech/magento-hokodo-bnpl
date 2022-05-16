<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Model;

use Hokodo\BNPL\Api\GuestSetUserServiceInterface;
use Hokodo\BNPL\Api\SetUserServiceInterface;
use Magento\Quote\Model\QuoteIdMaskFactory;

/**
 * Class Hokodo\BNPL\Model\GuestSetUserService.
 */
class GuestSetUserService implements GuestSetUserServiceInterface
{
    /**
     * @var SetUserServiceInterface
     */
    private $setUserService;

    /**
     * @var QuoteIdMaskFactory
     */
    private $quoteIdMaskFactory;

    /**
     * @param SetUserServiceInterface $setUserService
     * @param QuoteIdMaskFactory      $quoteIdMaskFactory
     */
    public function __construct(
        SetUserServiceInterface $setUserService,
        QuoteIdMaskFactory $quoteIdMaskFactory
    ) {
        $this->setUserService = $setUserService;
        $this->quoteIdMaskFactory = $quoteIdMaskFactory;
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\GuestSetUserServiceInterface::setUser()
     */
    public function setUser($cartId, \Hokodo\BNPL\Api\Data\UserInterface $user)
    {
        $quoteIdMask = $this->quoteIdMaskFactory->create()->load($cartId, 'masked_id');
        $quoteId = $quoteIdMask->getQuoteId();
        return $this->setUserService->setUser($quoteIdMask->getQuoteId(), $user);
    }
}
