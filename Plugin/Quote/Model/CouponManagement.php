<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Hokodo\BNPL\Plugin\Quote\Model;

use Hokodo\BNPL\Api\Data\HokodoQuoteInterface;
use Hokodo\BNPL\Api\HokodoQuoteRepositoryInterface;
use Magento\Checkout\Model\Session;
use Magento\Quote\Model\CouponManagement as QuoteCouponManagement;

class CouponManagement
{
    /**
     * @var Session
     */
    private Session $checkoutSession;

    /**
     * @var HokodoQuoteRepositoryInterface
     */
    private HokodoQuoteRepositoryInterface $hokodoQuoteRepository;

    /**
     * @param Session                        $checkoutSession
     * @param HokodoQuoteRepositoryInterface $hokodoQuoteRepository
     */
    public function __construct(
        Session $checkoutSession,
        HokodoQuoteRepositoryInterface $hokodoQuoteRepository
    ) {
        $this->checkoutSession = $checkoutSession;
        $this->hokodoQuoteRepository = $hokodoQuoteRepository;
    }

    /**
     * Invalidate offer if coupon was applied.
     *
     * @param QuoteCouponManagement $subject
     * @param bool                  $result
     *
     * @return mixed
     */
    public function afterSet(QuoteCouponManagement $subject, $result)
    {
        $this->invalidateOffer($result);

        return $result;
    }

    /**
     * Invalidate offer if coupon was removed.
     *
     * @param QuoteCouponManagement $subject
     * @param bool                  $result
     *
     * @return mixed
     */
    public function afterRemove(QuoteCouponManagement $subject, $result)
    {
        $this->invalidateOffer($result);

        return $result;
    }

    /**
     * Invalidate offer method.
     *
     * @param bool $result
     *
     * @return void
     */
    private function invalidateOffer(bool $result)
    {
        if ($result
            && ($hokodoQuote = $this->hokodoQuoteRepository->getByQuoteId($this->checkoutSession->getQuoteId()))
        ) {
            $this->hokodoQuoteRepository->save($hokodoQuote->setPatchType(HokodoQuoteInterface::PATCH_ALL));
        }
    }
}
