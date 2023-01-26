<?php

declare(strict_types=1);

namespace Hokodo\BNPL\Model\Data\Company;

use Hokodo\BNPL\Api\Data\Company\CreditLimitInterface;
use Magento\Framework\DataObject;

class CreditLimit extends DataObject implements CreditLimitInterface
{
    /**
     * @inheritdoc
     */
    public function getCurrency(): string
    {
        return $this->getData(self::CURRENCY);
    }

    /**
     * @inheritdoc
     */
    public function setCurrency(string $currency): self
    {
        $this->setData(self::CURRENCY, $currency);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getAmountAvailable(): int
    {
        return $this->getData(self::AMOUNT_AVAILABLE);
    }

    /**
     * @inheritdoc
     */
    public function setAmountAvailable(int $amountAvailable): self
    {
        $this->setData(self::AMOUNT_AVAILABLE, $amountAvailable);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getAmountInUse(): int
    {
        return $this->getData(self::AMOUNT_IN_USE);
    }

    /**
     * @inheritdoc
     */
    public function setAmountInUse(int $amountInUse): self
    {
        $this->setData(self::AMOUNT_IN_USE, $amountInUse);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getAmount(): int
    {
        return $this->getData(self::AMOUNT);
    }

    /**
     * @inheritdoc
     */
    public function setAmount(int $amount): self
    {
        $this->setData(self::AMOUNT, $amount);
        return $this;
    }
}
