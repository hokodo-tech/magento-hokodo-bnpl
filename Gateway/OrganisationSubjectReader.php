<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Gateway;

use Hokodo\BNPL\Api\Data\OrganisationInterface;

/**
 * Class Hokodo\BNPL\Gateway\OrganisationSubjectReader.
 */
class OrganisationSubjectReader extends SubjectReader
{
    /**
     * A function that read organisation.
     *
     * @param array $subject
     *
     * @throws \InvalidArgumentException
     *
     * @return \Hokodo\BNPL\Api\Data\OrganisationInterface
     */
    public function readOrganisation(array $subject)
    {
        $organization = $this->readFieldValue('organisations', $subject);

        if (!($organization instanceof OrganisationInterface)) {
            throw new \InvalidArgumentException('Organization field should be provided');
        }

        return $organization;
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Gateway\SubjectReader::readEndpointParam()
     */
    public function readEndpointParam($param, array $subject)
    {
        if ($param != 'id') {
            throw new \InvalidArgumentException('For endpoint organization param should be id');
        }
        return $this->readOrganisation($subject)->getId();
    }
}
