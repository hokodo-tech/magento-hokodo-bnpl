<?php
/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Hokodo\BNPL\Setup\Patch\Data;

use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Sales\Model\Order;
use Magento\Sales\Model\Order\StatusFactory;
use Magento\Sales\Model\ResourceModel\Order\Status as StatusResource;
use Magento\Sales\Model\ResourceModel\Order\StatusFactory as StatusResourceFactory;

class AddHokodoPendingPayment implements DataPatchInterface
{
    /**
     * @var StatusFactory
     */
    private StatusFactory $statusFactory;

    /**
     * @var StatusResourceFactory
     */
    private StatusResourceFactory $statusResourceFactory;

    /**
     * @param StatusFactory         $statusFactory
     * @param StatusResourceFactory $statusResourceFactory
     */
    public function __construct(
        StatusFactory $statusFactory,
        StatusResourceFactory $statusResourceFactory
    ) {
        $this->statusFactory = $statusFactory;
        $this->statusResourceFactory = $statusResourceFactory;
    }

    /**
     * Add Hokodo Pending Payment order status.
     *
     * @return void
     */
    public function apply(): void
    {
        /** @var StatusResource $statusResource */
        $statusResource = $this->statusResourceFactory->create();

        $status = $this->statusFactory->create();

        $status->setData([
            'status' => 'hokodo_pending_upfront_payment',
            'label' => 'Pending Upfront Payment',
        ]);

        try {
            $statusResource->save($status);
            $status->assignState(Order::STATE_PAYMENT_REVIEW, false, true);
        } catch (\Exception $exception) {
            return;
        }
    }

    /**
     * Get Dependencies.
     *
     * @return array
     */
    public static function getDependencies(): array
    {
        return [];
    }

    /**
     * Get Aliases.
     *
     * @return array
     */
    public function getAliases(): array
    {
        return [];
    }
}
