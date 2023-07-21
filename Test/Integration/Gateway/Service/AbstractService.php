<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Hokodo\BNPL\Test\Integration\Gateway\Service;

use Magento\TestFramework\Helper\Bootstrap;
use PHPUnit\Framework\TestCase;

/**
 * @property \Hokodo\BNPL\Gateway\Command\CommandPool|mixed $commandPool
 * @property \Magento\Framework\ObjectManagerInterface      $objectManager
 *
 * @magentoAppArea adminhtml
 *
 * @magentoAppIsolation enabled
 */
abstract class AbstractService extends TestCase
{
    /**
     * @var array
     */
    protected $httpResponse = [];

    /**
     * @return void
     *
     * @magentoApiDataFixture Hokodo_BNPL::Test/Fixtures/api_responses.php
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->objectManager = Bootstrap::getObjectManager();

        $clientMock = $this->getMockBuilder(\Hokodo\BNPL\Gateway\Http\Client\GuzzleHttpClient::class)
            ->setMockClassName('http_mock')
            ->disableOriginalConstructor()
            ->getMock();

        $clientMock
            ->method('placeRequest')
            ->willReturn($this->httpResponse);
        $this->objectManager->addSharedInstance($clientMock, \Hokodo\BNPL\Gateway\Http\Client\GuzzleHttpClient::class);
        $this->commandPool = $this->objectManager->get(\Hokodo\BNPL\Gateway\Command\CommandPool::class);
    }
}
