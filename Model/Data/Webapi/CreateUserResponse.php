<?php
declare(strict_types=1);

namespace Hokodo\BNPL\Model\Data\Webapi;

use Hokodo\BNPL\Api\Data\Webapi\CreateUserResponseInterface;
use Magento\Framework\Api\AbstractSimpleObject;

class CreateUserResponse extends AbstractSimpleObject implements CreateUserResponseInterface
{
    /**
     * @inheritdoc
     */
    public function getId(): string
    {
        return $this->_get(self::USER_ID);
    }

    /**
     * @inheritdoc
     */
    public function setId(string $id): self
    {
        $this->setData(self::USER_ID, $id);
        return $this;
    }
}
