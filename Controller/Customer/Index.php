<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Hokodo\BNPL\Controller\Customer;

use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;

class Index implements HttpGetActionInterface
{
    /**
     * @var CustomerSession|null
     */
    private ?CustomerSession $customerSession;

    /**
     * @var ResultFactory
     */
    private ResultFactory $resultFactory;

    /**
     * @param ResultFactory        $resultFactory
     * @param CustomerSession|null $customerSession
     */
    public function __construct(
        ResultFactory $resultFactory,
        CustomerSession $customerSession = null
    ) {
        $this->resultFactory = $resultFactory;
        $this->customerSession = $customerSession ?: ObjectManager::getInstance()->get(CustomerSession::class);
    }

    /**
     * Execute.
     *
     * @return ResultInterface
     */
    public function execute(): ResultInterface
    {
        if (!$this->customerSession->isLoggedIn()) {
            return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)
                ->setPath('customer/account/login');
        }
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $resultPage->getConfig()->getTitle()->set(__('My Company'));
        return $resultPage;
    }
}
