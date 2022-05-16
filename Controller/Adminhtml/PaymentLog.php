<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Hokodo\BNPL\Controller\Adminhtml;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;

/**
 * Class PaymentLog.
 */
abstract class PaymentLog extends Action
{
    /**
     * PageFactory.
     *
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * Constructor.
     *
     * @param Context     $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }

    /**
     * Load the page defined in view/adminhtml/layout/hokodo_payment_log_index.xml.
     *
     * @return Page
     */
    public function execute()
    {
        return $this->resultPageFactory->create();
    }
}
