<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Hokodo\BNPL\Model\Data\Gateway;

use Hokodo\BNPL\Api\Data\Gateway\CustomerAddressInterface;
use Hokodo\BNPL\Api\Data\Gateway\OrderCustomerInterface;
use Magento\Framework\Api\AbstractSimpleObject;

class OrderCustomer extends AbstractSimpleObject implements OrderCustomerInterface
{
    /**
     * @inheritdoc
     */
    public function setType(string $type): self
    {
        $this->setData(self::TYPE, $type);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setOrganisation(string $organisation): self
    {
        $this->setData(self::ORGANISATION, $organisation);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setUser(string $user): self
    {
        $this->setData(self::USER, $user);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setDeliveryAddress(CustomerAddressInterface $deliveryAddress): self
    {
        $this->setData(self::DELIVERY_ADDRESS, $deliveryAddress);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setInvoiceAddress(CustomerAddressInterface $invoiceAddress): self
    {
        $this->setData(self::INVOICE_ADDRESS, $invoiceAddress);
        return $this;
    }
}
