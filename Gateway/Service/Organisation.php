<?php
/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Hokodo\BNPL\Gateway\Service;

use Hokodo\BNPL\Api\Data\Gateway\CreateOrganisationRequestInterface;
use Hokodo\BNPL\Gateway\Command\Result\OrganisationResultInterface;
use Magento\Framework\Exception\NotFoundException;
use Magento\Payment\Gateway\Command\CommandException;

class Organisation extends AbstractService
{
    /**
     * Create Organisation service command.
     *
     * @param OrganisationResultInterface $createOrganisationRequest
     *
     * @return ResultInterface|null
     *
     * @throws NotFoundException
     * @throws CommandException
     */
    public function createOrganisation(CreateOrganisationRequestInterface $createOrganisationRequest)
    {
        return $this->commandPool->get('organisation_create')->execute($createOrganisationRequest->__toArray());
    }
}
