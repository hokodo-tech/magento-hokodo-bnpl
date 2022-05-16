<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Api;

use Magento\Sales\Model\Order;

/**
 * Interface Hokodo\BNPL\Api\OrderDocumentsManagementInterface.
 */
interface OrderDocumentsManagementInterface
{
    /**
     * A function that set order.
     *
     * @param Order  $order
     * @param string $docType
     *
     * @return \Hokodo\BNPL\Api\Data\OrderDocumentsInterface
     */
    public function setDocuments(
        Order $order,
        $docType = 'invoice'
    );

    /**
     * A function that gets payment offer.
     *
     * @param string $docId
     * @param string $orderApiId
     *
     * @return \Hokodo\BNPL\Api\Data\OrderDocumentsInterface
     */
    public function getDocuments($docId, $orderApiId);
}
