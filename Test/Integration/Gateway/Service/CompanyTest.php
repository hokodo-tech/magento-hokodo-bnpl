<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Hokodo\BNPL\Test\Integration\Gateway\Service;

use Hokodo\BNPL\Api\Data\Company\CreditInterface;
use Hokodo\BNPL\Api\Data\Gateway\CompanyCreditRequestInterface;
use Hokodo\BNPL\Api\Data\Gateway\CompanySearchRequestInterface;
use Magento\Framework\Exception\NotFoundException;
use Magento\Payment\Gateway\Command\CommandException;

class CompanyTest extends AbstractService
{
    /**
     * @var \Hokodo\BNPL\Gateway\Service\Company|null
     */
    private $companyService;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        if ($this->getName() === 'testSearch') {
            $this->httpResponse = [
                'matches' => [
                    [
                        'url' => 'https://api-dev.hokodo.co/v1/companies/co-bqRyKAGaFrEEN8JMjWJiqk',
                        'id' => 'co-bqRyKAGaFrEEN8JMjWJiqk',
                        'country' => 'GB',
                        'name' => 'Hokodo Ltd',
                        'address' => '35 Kingsland Road, London, E2 8AA',
                        'city' => 'London',
                        'postcode' => 'E2 8AA',
                        'legal_form' => 'Private limited with share capital',
                        'sectors' => [
                            [
                                'system' => 'SIC2007',
                                'code' => '62012',
                            ],
                            [
                                'system' => 'SIC2007',
                                'code' => '64999',
                            ],
                            [
                                'system' => 'SIC2007',
                                'code' => '66220',
                            ],
                        ],
                        'creation_date' => '2018-02-20',
                        'identifiers' => [
                            [
                                'idtype' => 'reg_number',
                                'country' => 'GB',
                                'value' => '11215527',
                            ],
                        ],
                        'email' => '',
                        'phone' => '',
                        'status' => 'Active',
                        'accounts_type' => 'Total exemption full',
                        'confidence' => null,
                    ],
                    [
                        'url' => 'https://api-dev.hokodo.co/v1/companies/co-qKANPt8yzz8ycHHaxSHV6P',
                        'id' => 'co-qKANPt8yzz8ycHHaxSHV6P',
                        'country' => 'GB',
                        'name' => 'Hokodo Services Ltd',
                        'address' => '35 Kingsland Road, London, E2 8AA',
                        'city' => 'London',
                        'postcode' => 'E2 8AA',
                        'legal_form' => 'Private limited with share capital',
                        'sectors' => [
                            [
                                'system' => 'SIC2007',
                                'code' => '66220',
                            ],
                        ],
                        'creation_date' => '2018-05-09',
                        'identifiers' => [
                            [
                                'idtype' => 'reg_number',
                                'country' => 'GB',
                                'value' => '11351988',
                            ],
                        ],
                        'email' => '',
                        'phone' => '',
                        'status' => 'Active',
                        'accounts_type' => 'Total exemption full',
                        'confidence' => null,
                    ],
                ],
            ];
        }
        if ($this->getName() === 'testCredit') {
            $this->httpResponse = [
                'company' => 'co-6z3f3CrXK9PwCzjTeugcSB',
                'status' => 'eligible',
                'rejection_reason' => null,
                'credit_limit' => [
                    'currency' => 'EUR',
                    'amount' => 111111,
                    'amount_in_use' => 0,
                    'amount_available' => 111111,
                ],
            ];
        }
        parent::setUp();
        $this->companyService = $this->objectManager->get(\Hokodo\BNPL\Gateway\Service\Company::class);
    }

    /**
     * @throws NotFoundException
     * @throws CommandException
     */
    public function testSearch(): void
    {
        $request = $this->objectManager->get(CompanySearchRequestInterface::class);

        $result = $this->companyService->search(
            $request->setCountry('GB')->setRegNumber('1')
        );

        $this->assertInstanceOf(\Hokodo\BNPL\Api\Data\CompanyInterface::class, $result->getDataModel());
        $this->assertCount(2, $result->getList());

        $companies = $result->getList();
        $companyId = reset($companies)->getId();
        $this->assertEquals($this->httpResponse['matches'][0]['id'], $companyId);
    }

    public function testCredit()
    {
        $request = $this->objectManager->get(CompanyCreditRequestInterface::class);
        $result = $this->companyService->getCredit(
            $request->setCompanyId('1')->setCurrency('tt')
        );
        $this->assertInstanceOf(CreditInterface::class, $result->getDataModel());
        $credit = $result->getDataModel();
        $this->assertEquals($this->httpResponse['status'], $credit->getStatus());
        $this->assertEquals(
            $this->httpResponse['credit_limit']['amount_available'],
            $credit->getCreditLimit()->getAmountAvailable()
        );
    }
}
