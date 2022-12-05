<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Hokodo\BNPL\Controller\Adminhtml\PaymentLog;

use Magento\Backend\App\Action;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;

class Index extends Action implements HttpGetActionInterface
{
    /**
     * Checking if ACL is allowed.
     *
     * @return bool
     */
    protected function _isAllowed(): bool
    {
        return $this->_authorization->isAllowed('Hokodo_BNPL::payment');
    }

    /**
     * Index action.
     *
     * @return ResultInterface
     */
    public function execute(): ResultInterface
    {
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $resultPage->setActiveMenu('Hokodo_BNPL::PaymentLog');
        $resultPage->addBreadcrumb(__('System'), __('Hokodo'));
        $resultPage->addBreadcrumb(__('Logs'), __('Logs'));
        $resultPage->getConfig()->getTitle()->prepend(__('Hokodo BNPL Logs'));
        return $resultPage;
    }
}
