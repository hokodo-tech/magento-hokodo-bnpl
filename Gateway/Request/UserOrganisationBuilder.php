<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Gateway\Request;

use Hokodo\BNPL\Gateway\UserOrganisationSubjectReader;
use Hokodo\BNPL\Model\SaveLog as PaymentLogger;
use Magento\Payment\Gateway\Request\BuilderInterface;

/**
 * Class Hokodo\BNPL\Gateway\Request\UserOrganisationBuilder.
 */
class UserOrganisationBuilder implements BuilderInterface
{
    /**
     * @var UserOrganisationSubjectReader
     */
    private $subjectReader;

    /**
     * @var PaymentLogger
     */
    private $paymentLogger;

    /**
     * @param UserOrganisationSubjectReader $subjectReader
     * @param PaymentLogger                 $paymentLogger
     */
    public function __construct(
        UserOrganisationSubjectReader $subjectReader,
        PaymentLogger $paymentLogger
    ) {
        $this->subjectReader = $subjectReader;
        $this->paymentLogger = $paymentLogger;
    }

    /**
     * @inheritDoc
     *
     * @see \Magento\Payment\Gateway\Request\BuilderInterface::build()
     */
    public function build(array $buildSubject)
    {
        return [
            'body' => $this->buildUserBody($buildSubject),
        ];
    }

    /**
     * A function builds user body.
     *
     * @param array $buildSubject
     *
     * @return array
     */
    private function buildUserBody(array $buildSubject)
    {
        /**
         * @var \Hokodo\BNPL\Api\Data\OrganisationUserInterface $user
         */
        $user = $this->subjectReader->readOrganisationUser($buildSubject);
        $data = [
            'payment_log_content' => $user->__toArray(),
            'action_title' => 'UserOrganisationBuilder: buildUserBody',
            'status' => 1,
        ];
        $this->paymentLogger->execute($data);
        return $user->__toArray();
    }
}
