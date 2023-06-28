<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Hokodo\BNPL\Test\Integration\Gateway\Service;

use Hokodo\BNPL\Api\Data\Gateway\CreateOrganisationRequestInterface;
use Hokodo\BNPL\Api\Data\OrganisationInterface;
use Hokodo\BNPL\Gateway\Service\Organisation;

class OrganisationTest extends AbstractService
{
    /**
     * @var array
     */
    protected $httpResponse = [
        'id' => 'org-pHr6VCyzQJALUsePkatPBo',
        'unique_id' => 'c105b862-f1ba-4197-9d97-57db63196b00',
        'registered' => '2017-06-01T14:37:12Z',
        'company' => 'co-bqRyKAGaFrEEN8JMjWJiqk',
        'users' => [],
    ];

    /**
     * @return void
     *
     * @throws \Magento\Framework\Exception\NotFoundException
     * @throws \Magento\Payment\Gateway\Command\CommandException
     */
    public function testCreate()
    {
        $organisationService = $this->objectManager->get(Organisation::class);
        $createOrganisationRequest = $this->objectManager->get(CreateOrganisationRequestInterface::class);

        $result = $organisationService->createOrganisation($createOrganisationRequest);
        $organisation = $result->getDataModel();

        $this->assertInstanceOf(OrganisationInterface::class, $organisation);
        $this->assertEquals($this->httpResponse['id'], $organisation->getId());
    }
}
