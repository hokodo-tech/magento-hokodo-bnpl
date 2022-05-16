<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Gateway;

use Hokodo\BNPL\Api\Data\DeferredPaymentInterface;

/**
 * Class Hokodo\BNPL\Gateway\DeferredPaymentSubjectReader.
 */
class DeferredPaymentSubjectReader extends SubjectReader
{
    /**
     * A function that read deferred payment.
     *
     * @param array $subject
     *
     * @throws \InvalidArgumentException
     *
     * @return DeferredPaymentInterface
     */
    public function readDeferredPayment(array $subject)
    {
        $user = $this->readFieldValue('deferred_payments', $subject);

        if (!($user instanceof DeferredPaymentInterface)) {
            throw new \InvalidArgumentException('Deferred Payments field should be provided');
        }

        return $user;
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Gateway\SubjectReader::readEndpointParam()
     */
    public function readEndpointParam($param, array $subject)
    {
        if ($param != 'deferred_payment_id') {
            throw new \InvalidArgumentException('For endopoint Deferred Payments param should be deferred_payment_id');
        }
        return $this->readDeferredPayment($subject)->getId();
    }
}
