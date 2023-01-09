<?php
/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Service;

use Hokodo\BNPL\Api\Data\OrderDocumentsInterface;

/**
 * Class Hokodo\BNPL\Service\OrderDocumentsService.
 */
class OrderDocumentsService extends AbstractService
{
    /**
     * A function that creates the order document.
     *
     * @param string                  $orderApiId
     * @param OrderDocumentsInterface $orderDocument
     * @param string                  $storeId
     *
     * @return OrderDocumentsInterface
     */
    public function create(
        string $orderApiId,
        OrderDocumentsInterface $orderDocument,
        string $storeId
    ) {
        return $this->executeCommand(
            'create_order_documents',
            [
                'order_id' => $orderApiId,
                'document' => $orderDocument,
                'store_id' => $storeId,
            ]
        )->getDataModel();
    }

    /**
     * A function that returns view of order.
     *
     * @param string $orderDocument
     *
     * @return \Hokodo\BNPL\Api\Data\OrderDocumentsInterface
     */
    public function get(OrderDocumentsInterface $orderDocument)
    {
        return $this->executeCommand(
            'view_order_documents',
            [
                'orders' => $orderDocument,
            ]
        )->getDataModel();
    }
}
