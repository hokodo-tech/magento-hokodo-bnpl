<?php
/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Hokodo\BNPL\Plugin\Model\SalesRule;

use Hokodo\BNPL\Api\Data\HokodoQuoteInterface;
use Hokodo\BNPL\Api\HokodoQuoteRepositoryInterface;
use Hokodo\BNPL\Gateway\Config\Config;
use Magento\Quote\Model\Quote\Item\AbstractItem;
use Magento\SalesRule\Model\ResourceModel\Rule\Collection;
use Magento\SalesRule\Model\RulesApplier as MagentoRulesApplier;

class RulesApplier
{
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
     * After Apply rules plugin.
     *
     * @param MagentoRulesApplier $subject
     * @param array               $result
     * @param AbstractItem        $item
     * @param Collection          $rules
     * @param bool                $skipValidation
     * @param mixed               $couponCode
     *
     * @return array
     */
    public function afterApplyRules($subject, $result, $item, $rules, $skipValidation, $couponCode)
    {
        if ($this->isAppliedRulesChanged($item, $result)
            && $item->getQuote()->getPayment()->getMethod() === Config::CODE) {
            $hokodoQuote = $this->hokodoQuoteRepository->getByQuoteId($item->getQuote()->getId());
            if ($hokodoQuote->getId()) {
                $hokodoQuote
                    ->setOfferId('')
                    ->setPatchType(HokodoQuoteInterface::PATCH_ITEMS);
                $this->hokodoQuoteRepository->save($hokodoQuote);
            }
        }

        return $result;
    }

    /**
     * Check if the applied rules have changed.
     *
     * @param AbstractItem $item
     * @param array        $result
     *
     * @return bool
     */
    private function isAppliedRulesChanged(AbstractItem $item, array $result)
    {
        $quoteRuleIds = $item->getAppliedRuleIds() ? explode(',', $item->getAppliedRuleIds()) : [];
        if (count($quoteRuleIds) === count($result)) {
            return count(array_diff($quoteRuleIds, $result)) > 0;
        }
        return true;
    }
}
