<?php

/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Hokodo\BNPL\Gateway\Request\SubjectReader;

use Magento\Framework\Exception\ConfigurationMismatchException;
use Magento\Quote\Api\Data\PaymentInterface;

class PaymentAdditionalInformation implements SubjectReaderInterface
{
    /**
     * @var string|null
     */
    private ?string $param;

    /**
     * @param string|null $param
     */
    public function __construct(
        ?string $param = null
    ) {
        $this->param = $param;
    }

    /**
     * @inheritDoc
     *
     * @throws ConfigurationMismatchException
     */
    public function readSubjectParam(array $subject): string
    {
        if (!$this->param) {
            throw new ConfigurationMismatchException(__('Endpoint parameter is missing to read from subject'));
        }
        /** @var PaymentInterface $payment */
        $payment = $subject['payment']->getPayment();
        return $payment->getAdditionalInformation()[$this->param];
    }
}
