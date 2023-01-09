<?php
/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Gateway\Request;

use Hokodo\BNPL\Gateway\UserOrganisationSubjectReader;
use Magento\Payment\Gateway\Request\BuilderInterface;
use Psr\Log\LoggerInterface as Logger;

/**
 * Class Hokodo\BNPL\Gateway\Request\UserOrganisationBuilder.
 */
class UserOrganisationBuilder implements BuilderInterface
{
    /**
     * @var UserOrganisationSubjectReader
     */
    private UserOrganisationSubjectReader $subjectReader;

    /**
     * @var Logger
     */
    private Logger $logger;

    /**
     * @param UserOrganisationSubjectReader $subjectReader
     * @param logger                        $logger
     */
    public function __construct(
        UserOrganisationSubjectReader $subjectReader,
        Logger $logger
    ) {
        $this->subjectReader = $subjectReader;
        $this->logger = $logger;
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
        $this->logger->debug(__METHOD__, $data);
        return $user->__toArray();
    }
}
