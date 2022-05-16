<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Gateway;

use Hokodo\BNPL\Api\Data\OrganisationUserInterface;

/**
 * Class Hokodo\BNPL\Gateway\UserOrganisationSubjectReader.
 */
class UserOrganisationSubjectReader extends SubjectReader
{
    /**
     * @var OrganisationSubjectReader
     */
    private $organisationSubjectReader;

    /**
     * @param OrganisationSubjectReader $organisationSubjectReader
     */
    public function __construct(
        OrganisationSubjectReader $organisationSubjectReader
    ) {
        $this->organisationSubjectReader = $organisationSubjectReader;
    }

    /**
     * A function that that reads organisation of user.
     *
     * @param array $subject
     *
     * @throws \InvalidArgumentException
     *
     * @return \Hokodo\BNPL\Api\Data\OrganisationUserInterface
     */
    public function readOrganisationUser(array $subject)
    {
        $user = $this->readFieldValue('user_organisation', $subject);

        if (!($user instanceof OrganisationUserInterface)) {
            throw new \InvalidArgumentException('User organisation field should be provided');
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
        if ($param == 'organisation_id') {
            return $this->organisationSubjectReader->readEndpointParam('id', $subject);
        }

        if ($param == 'user_id') {
            return $this->readOrganisationUser($subject)->getId();
        }

        return parent::readEndpointParam($param, $subject);
    }
}
