<?php
/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Service;

use Hokodo\BNPL\Api\Data\DeferredPaymentInterface;

/**
 * Class Hokodo\BNPL\Service\DeferredPaymentService.
 */
class DeferredPaymentService extends AbstractService
{
    /**
     * A function that returns deferred payment view.
     *
     * @param DeferredPaymentInterface $deferredPayment
     *
     * @return DeferredPaymentInterface
     */
    public function get(DeferredPaymentInterface $deferredPayment)
    {
        return $this->executeCommand('deferred_payment_view', [
            'deferred_payments' => $deferredPayment,
        ])->getDataModel();
    }

    /**
     * A function that returns list of deferred payment.
     *
     * @return DeferredPaymentInterface[]
     */
    public function getList()
    {
        return $this->executeCommand('deferred_payment_list', [])->getList();
    }
}
