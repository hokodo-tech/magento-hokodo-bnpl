<?php
/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Hokodo\BNPL\Controller\Customer;

use Hokodo\BNPL\Gateway\Config\Config;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\StoreManagerInterface;

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
     * @var \Magento\Framework\UrlInterface
     */
    private UrlInterface $urlBuilder;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private StoreManagerInterface $storeManager;

    /**
     * @var \Hokodo\BNPL\Gateway\Config\Config
     */
    private Config $config;

    /**
     * @param ResultFactory         $resultFactory
     * @param UrlInterface          $urlBuilder
     * @param StoreManagerInterface $storeManager
     * @param Config                $config
     * @param CustomerSession|null  $customerSession
     */
    public function __construct(
        ResultFactory $resultFactory,
        UrlInterface $urlBuilder,
        StoreManagerInterface $storeManager,
        Config $config,
        CustomerSession $customerSession = null
    ) {
        $this->resultFactory = $resultFactory;
        $this->urlBuilder = $urlBuilder;
        $this->storeManager = $storeManager;
        $this->config = $config;
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

        try {
            $currentStore = $this->storeManager->getStore();
        } catch (NoSuchEntityException $e) {
            return $this->getCustomerAccountRedirect();
        }

        if (!$this->config->isAllowCustomerToChangeCompany((int) $currentStore->getId())) {
            return $this->getCustomerAccountRedirect();
        }

        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $resultPage->getConfig()->getTitle()->set(__('My Company'));
        return $resultPage;
    }

    /**
     * Get Redirect to the Customer Account Page.
     *
     * @return ResultInterface
     */
    public function getCustomerAccountRedirect(): ResultInterface
    {
        $url = $this->urlBuilder->getUrl('customer/account', ['_secure' => true]);
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $resultRedirect->setUrl($url);
        return $resultRedirect;
    }
}
