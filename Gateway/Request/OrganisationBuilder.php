<?php
/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Gateway\Request;

use Hokodo\BNPL\Gateway\OrganisationSubjectReader;
use Magento\Payment\Gateway\Request\BuilderInterface;

/**
 * Class Hokodo\BNPL\Gateway\Request\OrganisationBuilder.
 */
class OrganisationBuilder implements BuilderInterface
{
    /**
     * @var OrganisationSubjectReader
     */
    private $subjectReader;

    /**
     * @param OrganisationSubjectReader $subjectReader
     */
    public function __construct(OrganisationSubjectReader $subjectReader)
    {
        $this->subjectReader = $subjectReader;
    }

    /**
     * @inheritDoc
     *
     * @see \Magento\Payment\Gateway\Request\BuilderInterface::build()
     */
    public function build(array $buildSubject)
    {
        return [
            'body' => $this->buildOrganisationBody($buildSubject),
        ];
    }

    /**
     * A function builds organisation body.
     *
     * @param array $buildSubject
     *
     * @throws \InvalidArgumentException
     *
     * @return array
     */
    private function buildOrganisationBody(array $buildSubject)
    {
        /**
         * @var \Hokodo\BNPL\Api\Data\OrganisationInterface $organization
         */
        $organization = $this->subjectReader->readOrganisation($buildSubject);

        if (!$organization->getUniqueId()) {
            throw new \InvalidArgumentException('Organisation should be created');
        }

        $organizationSubject = [
            'unique_id' => $organization->getUniqueId(),
            'registered' => $organization->getRegistered(),
            'company' => $organization->getCompany(),
        ];

        if ($organization->getId()) {
            $organizationSubject['id'] = $organization->getId();
        }

        return $organizationSubject;
    }
}
