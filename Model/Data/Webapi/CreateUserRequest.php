<?php
/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Hokodo\BNPL\Model\Data\Webapi;

use Hokodo\BNPL\Api\Data\Webapi\CreateUserRequestInterface;
use Magento\Framework\DataObject;

class CreateUserRequest extends DataObject implements CreateUserRequestInterface
{
    /**
     * @inheritDoc
     */
    public function getEmail(): string
    {
        return $this->getData(self::EMAIL);
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
    public function getName(): string
    {
        return $this->getData(self::NAME);
    }

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
    public function getOrganisationId(): string
    {
        return $this->getData(self::ORGANISATION_ID);
    }

    /**
     * @inheritDoc
     */
    public function setOrganisationId(string $organisationId): self
    {
        $this->setData(self::ORGANISATION_ID, $organisationId);
        return $this;
    }
}
