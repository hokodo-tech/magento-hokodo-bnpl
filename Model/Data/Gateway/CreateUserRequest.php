<?php
/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Hokodo\BNPL\Model\Data\Gateway;

use Hokodo\BNPL\Api\Data\Gateway\CreateUserRequestInterface;
use Magento\Framework\Api\AbstractSimpleObject;

class CreateUserRequest extends AbstractSimpleObject implements CreateUserRequestInterface
{
    /**
     * @inheritDoc
     */
    public function setName(string $name): self
    {
        $this->setData(self::NAME, $name);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setEmail(string $email): self
    {
        $this->setData(self::EMAIL, $email);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setRegistered(string $registered): self
    {
        $this->setData(self::REGISTERED, $registered);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setOrganisations(array $organisations): self
    {
        $this->setData(self::ORGANISATIONS, $organisations);
        return $this;
    }
}
