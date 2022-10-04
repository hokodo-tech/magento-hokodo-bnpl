<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Hokodo\BNPL\Gateway;

use InvalidArgumentException;
use Magento\Payment\Gateway\Data\OrderAdapterInterface;
use Magento\Payment\Gateway\Data\PaymentDataObject;
use Magento\Payment\Model\InfoInterface;

/**
 * Class Hokodo\BNPL\Gateway\DeferredPaymentOrderSubjectReader.
 */
class DeferredPaymentOrderSubjectReader extends SubjectReader
{
    /**
     * A function that read payment.
     *
     * @param array $subject
     *
     * @throws InvalidArgumentException
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
     * @return OrderAdapterInterface
     */
    public function readOrder(array $subject)
    {
        /**
         * @var PaymentDataObject $paymentDO
         */
        $paymentDO = $this->readFieldValue('payment', $subject);
        return $paymentDO->getOrder();
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Gateway\SubjectReader::readEndpointParam()
     */
    public function readEndpointParam($param, array $subject)
    {
        if ($param != 'hokodo_deferred_payment_id') {
            throw new InvalidArgumentException(__('Param should be hokodo_deferred_payment_id'));
        }
        $payment = $this->readPayment($subject);

        return $payment->getAdditionalInformation($param);
    }
}
