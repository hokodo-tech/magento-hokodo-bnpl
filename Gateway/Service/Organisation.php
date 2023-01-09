<?php
/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Hokodo\BNPL\Gateway\Service;

use Hokodo\BNPL\Api\Data\Gateway\CreateOrganisationRequestInterface;
use Magento\Framework\Exception\NotFoundException;
use Magento\Payment\Gateway\Command\CommandException;
use Magento\Payment\Gateway\Command\ResultInterface;

class Organisation extends AbstractService
{
    /**
     * Create Organisation service command.
     *
     * @param CreateOrganisationRequestInterface $createOrganisationRequest
     *
     * @return ResultInterface|null
     *
     * @throws NotFoundException
     * @throws CommandException
     */
    public function createOrganisation(CreateOrganisationRequestInterface $createOrganisationRequest): ?ResultInterface
    {
        return $this->commandPool->get('sdk_organisation_create')->execute($createOrganisationRequest->__toArray());
    }
}
