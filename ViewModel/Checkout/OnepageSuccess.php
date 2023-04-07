<?php
/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Hokodo\BNPL\ViewModel\Checkout;

use Magento\Checkout\Model\Session;
use Magento\Customer\CustomerData\Customer as CustomerData;
use Magento\Framework\App\ProductMetadataInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Quote\Model\ResourceModel\Quote\QuoteIdMask;

class OnepageSuccess implements ArgumentInterface
{
    /**
     * @var Session
     */
    private Session $session;

    /**
     * @var CustomerData
     */
    private CustomerData $customerData;

    /**
     * @var QuoteIdMask
     */
    private QuoteIdMask $quoteIdMaskResource;

    /**
     * @var ProductMetadataInterface
     */
    private ProductMetadataInterface $metadata;

    /**
     * OnepageSuccess constructor.
     *
     * @param Session                  $session
     * @param CustomerData             $customerData
     * @param QuoteIdMask              $quoteIdMaskResource
     * @param ProductMetadataInterface $metadata
     */
    public function __construct(
        Session $session,
        CustomerData $customerData,
        QuoteIdMask $quoteIdMaskResource,
        ProductMetadataInterface $metadata
    ) {
        $this->session = $session;
        $this->customerData = $customerData;
        $this->quoteIdMaskResource = $quoteIdMaskResource;
        $this->metadata = $metadata;
    }

    /**
     * Return payment method code.
     *
     * @return string
     */
    public function getSelectedPaymentMethodCode()
    {
        return $this->getOrder()->getPayment()->getMethod();
    }

    /**
     * Return last real order.
     *
     * @return \Magento\Sales\Model\Order
     */
    public function getOrder()
    {
        return $this->session->getLastRealOrder();
    }

    /**
     * Check can push Analytics or not.
     *
     * @return bool
     */
    public function canPushAnalytics(): bool
    {
        $canPush = true;
        $customerSectionData = $this->customerData->getSectionData();
        if (isset($customerSectionData['canPushAnalytics'])) {
            $canPush = $customerSectionData['canPushAnalytics'];
        }
        return $canPush;
    }

    /**
     * Get quote Id.
     *
     * @return string
     */
    public function getQuoteId(): string
    {
        $quoteId = $this->getOrder()->getQuoteId();
        if (!$this->getOrder()->getCustomerId()) {
            /*
             * We need to use masked quote id for guests.
             * @todo remove it when support of 2.3.7 ends
             */
            if (!$this->isOldMagentoVersion()) {
                $quoteId = $this->quoteIdMaskResource->getMaskedQuoteId((int) $quoteId);
            } else {
                $quoteId = $this->getMaskedQuoteId((int) $quoteId);
            }
        }
        return (string) $quoteId;
    }

    /**
     * Check if magento version older than 2.4.0.
     *
     * @return bool
     */
    public function isOldMagentoVersion(): bool
    {
        return (bool) version_compare($this->metadata->getVersion(), '2.4.0', '<');
    }

    /**
     * Magento 2.3.7 compatibility because it has no getMaskedQuoteId($quoteId) in resource model.
     *
     * @param int $quoteId
     *
     * @return string|null
     */
    public function getMaskedQuoteId(int $quoteId): ?string
    {
        try {
            $connection = $this->quoteIdMaskResource->getConnection();
            $mainTable = $this->quoteIdMaskResource->getMainTable();
            $field = $connection->quoteIdentifier(sprintf('%s.%s', $mainTable, 'quote_id'));

            $select = $connection->select()
                ->from($mainTable, ['masked_id'])
                ->where($field . '=?', $quoteId);

            $result = $connection->fetchOne($select);
        } catch (LocalizedException $e) {
            $result = null;
        }

        return $result ?: null;
    }
}
