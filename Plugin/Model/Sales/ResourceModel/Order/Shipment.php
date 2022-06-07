<?php

declare(strict_types=1);
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Plugin\Model\Sales\ResourceModel\Order;

use Hokodo\BNPL\Api\OrderDocumentsManagementInterface;
use Hokodo\BNPL\Model\PostSaleProcessor;
use Magento\Framework\Model\AbstractModel;
use Magento\Sales\Api\Data\ShipmentInterface;
use Magento\Sales\Model\ResourceModel\Order\Shipment as ShipmentResource;

/**
 * Class Hokodo\BNPL\Plugin\Model\Sales\ResourceModel\Order\Shipment.
 */
class Shipment
{
    /**
     * @var PostSaleProcessor
     */
    protected $postSaleProcessor;

    /**
     * @var OrderDocumentsManagementInterface
     */
    private $orderDocumentManagement;

    /**
     * A construct.
     *
     * @param PostSaleProcessor                 $postSaleProcessor
     * @param OrderDocumentsManagementInterface $orderDocumentManagement
     */
    public function __construct(
        PostSaleProcessor $postSaleProcessor,
        OrderDocumentsManagementInterface $orderDocumentManagement
    ) {
        $this->postSaleProcessor = $postSaleProcessor;
        $this->orderDocumentManagement = $orderDocumentManagement;
    }

    /**
     * A function that save result.
     *
     * @param ShipmentResource $subject
     * @param ShipmentResource $result
     * @param AbstractModel    $shipment
     *
     * @return ShipmentResource
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterSave(
        ShipmentResource $subject,
        ShipmentResource $result,
        AbstractModel $shipment
    ) {
        if (!empty($shipment->getId())) {
            /** @var ShipmentInterface $shipment */
            $order = $shipment->getOrder();

            if ($order->getPayment()->getMethod() === 'hokodo_bnpl') {
                $this->postSaleProcessor->fulfill($shipment);
                $this->orderDocumentManagement->setDocuments($order, 'shipping');
            }
        }

        return $result;
    }
}
