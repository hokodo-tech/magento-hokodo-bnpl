<?php
declare(strict_types=1);

namespace Hokodo\BNPL\Model\Data\Gateway;

use Hokodo\BNPL\Api\Data\Gateway\CreateUserRequestInterface;
use Magento\Framework\Api\AbstractSimpleObject;

class CreateUserRequest extends AbstractSimpleObject implements CreateUserRequestInterface
{
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
    public function setEmail(string $email): self
    {
        $this->setData(self::EMAIL, $email);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setRegistered(string $registered): self
    {
        $this->setData(self::REGISTERED, $registered);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setOrganisations(array $organisations): self
    {
        $this->setData(self::ORGANISATIONS, $organisations);
        return $this;
    }
}
