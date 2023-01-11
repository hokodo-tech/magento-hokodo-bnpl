<?php
/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Api;

use Magento\Sales\Api\Data\CreditmemoInterface;
use Magento\Sales\Api\Data\InvoiceInterface;
use Magento\Sales\Api\Data\ShipmentInterface;

/**
 * Interface Hokodo\BNPL\Api\OrderDocumentsManagementInterface.
 */
interface OrderDocumentsManagementInterface
{
    /**
     * Set document to Hokodo.
     *
     * @param CreditmemoInterface|InvoiceInterface|ShipmentInterface $document
     * @param string                                                 $doctype
     * @param string                                                 $hokodoOrderId
     *
     * @return void
     */
    public function setDocument($document, string $doctype, string $hokodoOrderId): void;
}
