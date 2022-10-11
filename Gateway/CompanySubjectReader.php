<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Gateway;

use Hokodo\BNPL\Api\Data\CompanyInterface;

/**
 * Class Hokodo\BNPL\Gateway\CompanySubjectReader.
 */
class CompanySubjectReader extends SubjectReader
{
    /**
     * A function that read company.
     *
     * @param array $subject
     *
     * @throws \InvalidArgumentException
     *
     * @return \Hokodo\BNPL\Api\Data\CompanyInterface
     */
    public function readCompany(array $subject)
    {
        $user = $this->readFieldValue('companies', $subject);

        if (!($user instanceof CompanyInterface)) {
            throw new \InvalidArgumentException(__('Companies field should be provided'));
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
        if ($param != 'id') {
            throw new \InvalidArgumentException(__('For endopoint companies param should be id'));
        }
        return $this->readCompany($subject)->getId();
    }
}
