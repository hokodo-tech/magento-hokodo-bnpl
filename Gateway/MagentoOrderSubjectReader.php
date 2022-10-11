<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Gateway;

use Magento\Payment\Gateway\Data\PaymentDataObject;
use Magento\Payment\Model\InfoInterface;
use Magento\Sales\Model\Order;

/**
 * Class Hokodo\BNPL\Gateway\MagentoOrderSubjectReader.
 */
class MagentoOrderSubjectReader extends SubjectReader
{
    /**
     * A function that read payment.
     *
     * @param array $subject
     *
     * @throws \InvalidArgumentException
     *
     * @return InfoInterface
     */
    public function readPayment(array $subject)
    {
        /**
         * @var PaymentDataObject $paymentDO
         */
        $paymentDO = $this->readFieldValue('payment', $subject);
        return $paymentDO->getPayment();
    }

    /**
     * A function that read order.
     *
     * @param array $subject
     *
     * @return Order
     */
    public function readOrder(array $subject)
    {
        return $this->readPayment($subject)->getOrder();
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Gateway\SubjectReader::readEndpointParam()
     */
    public function readEndpointParam($param, array $subject)
    {
        if ($param != 'order_id') {
            throw new \InvalidArgumentException(__('For endpoint order param should be order_id'));
        }

        return $this->readOrder($subject)->getOrderApiId();
    }
}
