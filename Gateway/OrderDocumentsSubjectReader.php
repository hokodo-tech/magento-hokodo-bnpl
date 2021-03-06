<?php

/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Gateway;

use Hokodo\BNPL\Api\Data\OrderDocumentsInterface;
use Hokodo\BNPL\Api\Data\OrderInformationInterface;
use Magento\Sales\Api\Data\InvoiceInterface;
use Magento\Sales\Api\Data\ShipmentInterface;

/**
 * Class Hokodo\BNPL\Gateway\OrderDocumentsSubjectReader.
 */
class OrderDocumentsSubjectReader extends SubjectReader
{
    /**
     * A function that read cart.
     *
     * @param array $subject
     *
     * @throws \InvalidArgumentException
     *
     * @return \Magento\Sales\Api\Data\InvoiceInterface
     */
    public function readInvoice(array $subject)
    {
        $invoice = $this->readFieldValue('invoice', $subject);

        if (!$invoice instanceof InvoiceInterface) {
            throw new \InvalidArgumentException('Invoice should be provided');
        }
        return $invoice;
    }

    /**
     * A function that read shipment.
     *
     * @param array $subject
     *
     * @throws \InvalidArgumentException
     *
     * @return \Magento\Sales\Api\Data\ShipmentInterface
     */
    public function readShipment(array $subject)
    {
        $shipment = $this->readFieldValue('shipment', $subject);

        if (!$shipment instanceof ShipmentInterface) {
            throw new \InvalidArgumentException('Shipment should be provided');
        }
        return $shipment;
    }

    /**
     * A function that read document.
     *
     * @param array $subject
     *
     * @throws \InvalidArgumentException
     *
     * @return \Hokodo\BNPL\Api\Data\OrderDocumentsInterface
     */
    public function readDocument(array $subject)
    {
        $document = $this->readFieldValue('document', $subject);

        if (!$document instanceof OrderDocumentsInterface) {
            throw new \InvalidArgumentException('Order document should be provided');
        }
        return $document;
    }

    /**
     * A function that read order information.
     *
     * @param array $subject
     *
     * @throws \InvalidArgumentException
     *
     * @return \Hokodo\BNPL\Api\Data\OrderInformationInterface
     */
    public function readOrder(array $subject)
    {
        $document = $this->readFieldValue('order_id', $subject);

        if (!$document instanceof OrderInformationInterface) {
            throw new \InvalidArgumentException('Order document should be provided');
        }
        return $document;
    }
}
