<?php
declare(strict_types=1);

namespace Hokodo\BNPL\Gateway\Service;

use Hokodo\BNPL\Api\Data\Gateway\CreateOrganisationRequestInterface;

class Organisation extends AbstractService
{
    public function createOrganisation(CreateOrganisationRequestInterface $createOrganisationRequest)
    {
        return $this->commandPool->get('sdk_organisation_create')->execute($createOrganisationRequest->__toArray());
    }
}
