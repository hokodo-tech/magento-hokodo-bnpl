<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Hokodo\BNPL\Model;

use Hokodo\BNPL\Api\Data\PaymentLogInterface;
use Hokodo\BNPL\Model\ResourceModel\PaymentLog as ResourceModel;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractModel;

/**
 * PaymentLog class for managing logs.
 */
class PaymentLog extends AbstractModel implements IdentityInterface, PaymentLogInterface
{
    /**
     * @const string Cache tag
     */
    public const CACHE_TAG = 'hokodo_payment_logs';

    /**
     * Model initialization.
     *
     * @return void
     */
    protected function _construct() // @codingStandardsIgnoreLine
    {
        $this->_init(ResourceModel::class);
    }

    /**
     * Retrieve cache identities.
     *
     * @return array|string[]
     */
    public function getIdentities(): array
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    /**
     * Get Entity ID.
     *
     * @return int|null
     */
    public function getPaymentLogId(): ?int
    {
        return (int) $this->getData(self::PAYMENT_LOG_ID);
    }

    /**
     * Get log content.
     *
     * @return string|null
     */
    public function getPaymentLogContent(): ?string
    {
        return $this->getData(self::PAYMENT_LOG_CONTENT);
    }

    /**
     * Get action title.
     *
     * @return string|null
     */
    public function getActionTitle(): ?string
    {
        return $this->getData(self::ACTION_TITLE);
    }

    /**
     * Get status.
     *
     * @return bool|null
     */
    public function getStatus(): ?bool
    {
        return $this->getData(self::STATUS);
    }

    /**
     * Get creation time.
     *
     * @return string|null
     */
    public function getCreatedAt(): ?string
    {
        return $this->getData(self::CREATED_AT);
    }

    /**
     * Set payment log id.
     *
     * @param string $paymentLogId
     *
     * @return PaymentLogInterface
     */
    public function setPaymentLogId(int $paymentLogId): PaymentLogInterface
    {
        $this->setData(self::PAYMENT_LOG_ID, $paymentLogId);

        return $this;
    }

    /**
     * Set payment log content.
     *
     * @param string $paymentLogContent
     *
     * @return PaymentLogInterface
     */
    public function setPaymentLogContent(string $paymentLogContent): PaymentLogInterface
    {
        $this->setData(self::PAYMENT_LOG_CONTENT, $paymentLogContent);

        return $this;
    }

    /**
     * Set action title.
     *
     * @param string|null $actionTitle
     *
     * @return $this
     */
    public function setActionTitle(string $actionTitle): PaymentLogInterface
    {
        $this->setData(self::ACTION_TITLE, $actionTitle);

        return $this;
    }

    /**
     * Set status.
     *
     * @param bool|null $status
     *
     * @return $this
     */
    public function setStatus(?bool $status): PaymentLogInterface
    {
        $this->setData(self::STATUS, $status);

        return $this;
    }

    /**
     * Set creation time.
     *
     * @param string $createdAt
     *
     * @return $this
     */
    public function setCreatedAt(string $createdAt): PaymentLogInterface
    {
        $this->setData(self::CREATED_AT, $createdAt);

        return $this;
    }
}
