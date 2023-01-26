<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Hokodo\BNPL\Controller\Adminhtml\Company;

use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\NotFoundException;

class Credit implements HttpPostActionInterface
{
    /**
     * @var RequestInterface
     */
    private RequestInterface $request;

    /**
     * @var ResultFactory
     */
    private ResultFactory $resultFactory;

    /**
     * @param RequestInterface $request
     * @param ResultFactory    $resultFactory
     */
    public function __construct(
        RequestInterface $request,
        ResultFactory $resultFactory
    ) {
        $this->request = $request;
        $this->resultFactory = $resultFactory;
    }

    /**
     * Execute action based on request and return result.
     *
     * @return ResultInterface|ResponseInterface
     *
     * @throws NotFoundException
     */
    public function execute()
    {
        $result = $this->resultFactory->create(\Magento\Framework\Controller\ResultFactory::TYPE_JSON);
        $result->setData(['total' => 'test']);

        return $result;
    }
}
