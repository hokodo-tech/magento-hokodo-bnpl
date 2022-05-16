<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Gateway;

use Hokodo\BNPL\Api\Data\UserInterface;
use InvalidArgumentException;

/**
 * Class Hokodo\BNPL\Gateway\UserSubjectReader.
 */
class UserSubjectReader extends SubjectReader
{
    /**
     * A function that reads user.
     *
     * @param array $subject
     *
     * @throws InvalidArgumentException
     *
     * @return UserInterface
     */
    public function readUser(array $subject): UserInterface
    {
        $user = $this->readFieldValue('users', $subject);

        if (!($user instanceof UserInterface)) {
            throw new InvalidArgumentException('User field should be provided');
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
            throw new InvalidArgumentException('For endopoint users param should be id');
        }
        return $this->readUser($subject)->getId();
    }
}
