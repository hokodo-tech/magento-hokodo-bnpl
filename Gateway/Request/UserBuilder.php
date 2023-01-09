<?php
/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Gateway\Request;

use Hokodo\BNPL\Gateway\UserSubjectReader;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Stdlib\DateTime\DateTimeFactory;
use Magento\Payment\Gateway\Request\BuilderInterface;

/**
 * Class Hokodo\BNPL\Gateway\Request\UserBuilder.
 */
class UserBuilder implements BuilderInterface
{
    /**
     * @var UserSubjectReader
     */
    private $subjectReader;

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var DateTimeFactory
     */
    private $dateFactory;

    /**
     * @param UserSubjectReader    $subjectReader
     * @param ScopeConfigInterface $scopeConfig
     * @param DateTimeFactory      $dateFactory
     */
    public function __construct(
        UserSubjectReader $subjectReader,
        ScopeConfigInterface $scopeConfig,
        DateTimeFactory $dateFactory
    ) {
        $this->subjectReader = $subjectReader;
        $this->scopeConfig = $scopeConfig;
        $this->dateFactory = $dateFactory;
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
     * A function that build user body.
     *
     * @param array $buildSubject
     *
     * @throws \InvalidArgumentException
     *
     * @return array
     */
    private function buildUserBody(array $buildSubject)
    {
        /**
         * @var \Hokodo\BNPL\Api\Data\UserInterface $user
         */
        $user = $this->subjectReader->readUser($buildSubject);

        $userData = $user->__toArray();

        if (!isset($userData['registered']) || !$userData['registered']) {
            $userData['registered'] = $this->dateFactory->create()->gmtDate();
        }

        return $userData;
    }
}
