<?php
/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Hokodo\BNPL\Model\Data\Webapi;

use Hokodo\BNPL\Api\Data\Webapi\CreateOrganisationResponseInterface;
use Magento\Framework\Api\AbstractSimpleObject;

class CreateOrganisationResponse extends AbstractSimpleObject implements CreateOrganisationResponseInterface
{
    /**
     * @inheritDoc
     */
    public function setId(string $id): self
    {
        $this->setData(self::ORGANISATION_ID, $id);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getId(): string
    {
        return $this->_get(self::ORGANISATION_ID);
    }
}
