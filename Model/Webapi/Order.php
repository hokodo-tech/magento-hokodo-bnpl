<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Hokodo\BNPL\Model\Webapi;

use Hokodo\BNPL\Api\Data\Webapi\CreateOrderRequestInterface;
use Hokodo\BNPL\Api\Data\Webapi\CreateOrderResponseInterface;
use Hokodo\BNPL\Api\Data\Webapi\CreateOrderResponseInterfaceFactory;
use Hokodo\BNPL\Api\Webapi\OrderInterface;

class Order implements OrderInterface
{
    /**
     * @var CreateOrderResponseInterfaceFactory
     */
    private CreateOrderResponseInterfaceFactory $responseInterfaceFactory;

    public function __construct(
        CreateOrderResponseInterfaceFactory $responseInterfaceFactory
    ) {
        $this->responseInterfaceFactory = $responseInterfaceFactory;
    }

    /**
     * Create order request webapi handler.
     *
     * @param CreateOrderRequestInterface $payload
     *
     * @return CreateOrderResponseInterface
     */
    public function create(CreateOrderRequestInterface $payload): CreateOrderResponseInterface
    {
        $response = $this->responseInterfaceFactory->create();
        /* @var $response CreateOrderResponseInterface */

        return $response->setId($payload->getQuoteId());
    }
}
