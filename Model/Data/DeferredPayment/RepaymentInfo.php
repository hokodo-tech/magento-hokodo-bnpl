<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Hokodo\BNPL\Model\Data\DeferredPayment;

use Hokodo\BNPL\Api\Data\DeferredPayment\RepaymentInfoInterface;

class RepaymentInfo implements RepaymentInfoInterface
{
    /**
     * @inheritdoc
     */
    public function getStatus(): string
    {
        return $this->getData(self::STATUS);
    }

    /**
     * @inheritdoc
     */
    public function setStatus(string $status): self
    {
        $this->setData(self::STATUS, $status);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getOutstandingAmount(): string
    {
        return $this->getData(self::OUTSTANDING_AMOUNT);
    }

    /**
     * @inheritdoc
     */
    public function setOutstandingAmount(string $outstandingAmount): self
    {
        $this->setData(self::OUTSTANDING_AMOUNT, $outstandingAmount);
        return $this;
    }
}
