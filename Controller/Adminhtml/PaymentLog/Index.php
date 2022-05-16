<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Hokodo\BNPL\Controller\Adminhtml\PaymentLog;

use Hokodo\BNPL\Controller\Adminhtml\PaymentLog;

class Index extends PaymentLog
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
     * @return void
     */
    public function execute()
    {
        $this->_view->loadLayout(); // @codingStandardsIgnoreLine
        $this->_setActiveMenu('Hokodo_BNPL::PaymentLog'); // @codingStandardsIgnoreLine
        $this->_view->getPage()->getConfig()->getTitle()->prepend(
            __('Hokodo BNPL Logs')
        );
        $this->_view->renderLayout(); // @codingStandardsIgnoreLine
    }
}
