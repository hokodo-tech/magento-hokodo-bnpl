<?php
declare(strict_types=1);

namespace Hokodo\BNPL\Model\Data\Webapi;

use Hokodo\BNPL\Api\Data\Webapi\CreateUserRequestInterface;
use Magento\Framework\DataObject;

class CreateUserRequest extends DataObject implements CreateUserRequestInterface
{
    /**
     * @inheritdoc
     */
    public function getEmail(): string
    {
        return $this->getData(self::EMAIL);
    }

    /**
     * @inheritdoc
     */
    public function setEmail(string $email): self
    {
        $this->setData(self::EMAIL, $email);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getName(): string
    {
        return $this->getData(self::NAME);
    }

    /**
     * @inheritdoc
     */
    public function setName(string $name): self
    {
        $this->setData(self::NAME, $name);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getOrganisationId(): string
    {
        return $this->getData(self::ORGANISATION_ID);
    }

    /**
     * @inheritdoc
     */
    public function setOrganisationId(string $organisationId): self
    {
        $this->setData(self::ORGANISATION_ID, $organisationId);
        return $this;
    }
}
