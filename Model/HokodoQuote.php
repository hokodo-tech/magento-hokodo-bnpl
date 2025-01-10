<?php

/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Hokodo\BNPL\Model;

use Hokodo\BNPL\Api\Data\HokodoQuoteInterface;
use Hokodo\BNPL\Model\ResourceModel\HokodoQuote as ResourceModel;
use Magento\Framework\Model\AbstractModel;

class HokodoQuote extends AbstractModel implements HokodoQuoteInterface
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'hokodo_quote_model';

    /**
     * Initialize magento model.
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(ResourceModel::class);
    }

    /**
     * @inheritdoc
     */
    public function getQuoteId(): ?int
    {
        $id = $this->getData(self::QUOTE_ID);
        return $id ? (int) $id : null;
    }

    /**
     * @inheritdoc
     */
    public function setQuoteId(int $quoteId): self
    {
        $this->setData(self::QUOTE_ID, $quoteId);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getCompanyId(): ?string
    {
        return $this->getData(self::COMPANY_ID);
    }

    /**
     * @inheritdoc
     */
    public function setCompanyId(string $companyId): self
    {
        $this->setData(self::COMPANY_ID, $companyId);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getUserId(): ?string
    {
        return $this->getData(self::USER_ID);
    }

    /**
     * @inheritdoc
     */
    public function setUserId(string $userId): self
    {
        $this->setData(self::USER_ID, $userId);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getOrganisationId(): ?string
    {
        return $this->getData(self::ORGANISATION_ID);
    }

    /**
     * @inheritdoc
     */
    public function setOrganisationId(string $organisationId): self
    {
        $this->setData(self::ORGANISATION_ID, $organisationId);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getOrderId(): ?string
    {
        return $this->getData(self::ORDER_ID);
    }

    /**
     * @inheritdoc
     */
    public function setOrderId(string $orderId): self
    {
        $this->setData(self::ORDER_ID, $orderId);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getOfferId(): ?string
    {
        return $this->getData(self::OFFER_ID);
    }

    /**
     * @inheritdoc
     */
    public function setOfferId(string $offerId): self
    {
        $this->setData(self::OFFER_ID, $offerId);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getPatchType(): ?int
    {
        if ($this->getData(self::PATCH_TYPE) === null) {
            return null;
        }
        return (int) $this->getData(self::PATCH_TYPE);
    }

    /**
     * @inheritdoc
     */
    public function setPatchType(?int $patchType): self
    {
        $currentPatchRequired = $this->getPatchType();
        if ($patchType !== null
            && $currentPatchRequired !== null
            && $currentPatchRequired !== $patchType
        ) {
            $patchType = self::PATCH_ALL;
        }
        $this->setData(self::PATCH_TYPE, $patchType);

        return $this;
    }
}
