<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Hokodo\BNPL\Model\Data\Webapi;

use Hokodo\BNPL\Api\Data\Webapi\CreateUserResponseInterface;
use Magento\Framework\Api\AbstractSimpleObject;

class CreateUserResponse extends AbstractSimpleObject implements CreateUserResponseInterface
{
    /**
     * @inheritDoc
     */
    public function getId(): string
    {
        return $this->_get(self::USER_ID);
    }

    /**
     * @inheritDoc
     */
    public function setId(string $id): self
    {
        $this->setData(self::USER_ID, $id);
        return $this;
    }
}
