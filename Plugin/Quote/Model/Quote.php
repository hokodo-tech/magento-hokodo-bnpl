<?php

/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Hokodo\BNPL\Plugin\Quote\Model;

use Hokodo\BNPL\Api\Data\HokodoQuoteInterface;
use Hokodo\BNPL\Api\HokodoQuoteRepositoryInterface;
use Magento\Quote\Model\Quote as MagentoQuote;

class Quote
{
    /**
     * @var null
     */
    private $totalBeforeCollection = null;

    /**
     * @var HokodoQuoteRepositoryInterface
     */
    private HokodoQuoteRepositoryInterface $hokodoQuoteRepository;

    /**
     * @param HokodoQuoteRepositoryInterface $hokodoQuoteRepository
     */
    public function __construct(
        HokodoQuoteRepositoryInterface $hokodoQuoteRepository
    ) {
        $this->hokodoQuoteRepository = $hokodoQuoteRepository;
    }

    /**
     * Compare grand total before/after collection and invalidate Hokodo quote.
     *
     * @param MagentoQuote $subject
     * @param MagentoQuote $result
     *
     * @return MagentoQuote
     */
    public function afterCollectTotals(MagentoQuote $subject, MagentoQuote $result)
    {
        if ($this->totalBeforeCollection !== $subject->getGrandTotal()) {
            $hokodoQuote = $this->hokodoQuoteRepository->getByQuoteId($subject->getId());
            if ($hokodoQuote->getQuoteId()) {
                $hokodoQuote
                    ->setOfferId('')
                    ->setPatchType(HokodoQuoteInterface::PATCH_ITEMS);
                $this->hokodoQuoteRepository->save($hokodoQuote);
            }
        }
        return $result;
    }

    /**
     * Save grand total before re-collect.
     *
     * @param MagentoQuote $subject
     *
     * @return void
     */
    public function beforeCollectTotals(MagentoQuote $subject)
    {
        $this->totalBeforeCollection = (float) $subject->getGrandTotal();
    }
}
