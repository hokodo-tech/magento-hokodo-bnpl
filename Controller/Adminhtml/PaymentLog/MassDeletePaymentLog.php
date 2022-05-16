<?php

declare(strict_types=1);
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Controller\Adminhtml\PaymentLog;

use Hokodo\BNPL\Model\ResourceModel\PaymentLog\CollectionFactory;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Ui\Component\MassAction\Filter;

class MassDeletePaymentLog extends Action
{
    /**
     * Filter variable.
     *
     * @var Filter
     */
    protected $filter;

    /**
     * CollectionFactory.
     *
     * @var CollectionFactory
     */
    protected $paymentLogCollectionFactory;

    /**
     * Constructor.
     *
     * @param Context           $context
     * @param Filter            $filter
     * @param CollectionFactory $paymentLogCollectionFactory
     */
    public function __construct(
        Context $context,
        Filter $filter,
        CollectionFactory $paymentLogCollectionFactory
    ) {
        parent::__construct($context);
        $this->filter = $filter;
        $this->paymentLogCollectionFactory = $paymentLogCollectionFactory;
    }

    /**
     * Checking if ACL is allowed.
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Hokodo_BNPL::payment');
    }

    /**
     * Execute.
     *
     * @return mixed
     *
     * @throws LocalizedException
     */
    public function execute()
    {
        $collection = $this->filter->getCollection(
            $this->paymentLogCollectionFactory->create()
        );
        $collectionSize = $collection->getSize();

        foreach ($collection as $item) {
            $item->delete();
        }
        $this->messageManager->addSuccess(__('A total of %1 record(s) have been deleted.', $collectionSize));

        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $resultRedirect->setPath('hokodo/paymentlog/index');
    }
}
