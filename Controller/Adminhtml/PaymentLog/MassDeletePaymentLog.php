<?php

declare(strict_types=1);
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Controller\Adminhtml\PaymentLog;

use Hokodo\BNPL\Api\Data\PaymentLogInterface;
use Hokodo\BNPL\Api\PaymentLogRepositoryInterface;
use Hokodo\BNPL\Model\ResourceModel\PaymentLog\CollectionFactory;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Ui\Component\MassAction\Filter;

class MassDeletePaymentLog extends Action implements HttpPostActionInterface
{
    /**
     * Filter variable.
     *
     * @var Filter
     */
    private Filter $filter;

    /**
     * @var CollectionFactory
     */
    private CollectionFactory $paymentLogCollectionFactory;

    /**
     * @var PaymentLogRepositoryInterface
     */
    private PaymentLogRepositoryInterface $paymentLogRepository;

    /**
     * @param Context                       $context
     * @param Filter                        $filter
     * @param CollectionFactory             $paymentLogCollectionFactory
     * @param PaymentLogRepositoryInterface $paymentLogRepository
     */
    public function __construct(
        Context $context,
        Filter $filter,
        CollectionFactory $paymentLogCollectionFactory,
        PaymentLogRepositoryInterface $paymentLogRepository
    ) {
        parent::__construct($context);
        $this->filter = $filter;
        $this->paymentLogCollectionFactory = $paymentLogCollectionFactory;
        $this->paymentLogRepository = $paymentLogRepository;
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
     * @return ResultInterface
     *
     * @throws LocalizedException
     */
    public function execute(): ResultInterface
    {
        $collection = $this->filter->getCollection(
            $this->paymentLogCollectionFactory->create()
        );
        $collectionSize = $collection->getSize();
        $error = 0;
        /** @var PaymentLogInterface $item */
        foreach ($collection as $item) {
            try {
                $this->paymentLogRepository->delete($item);
            } catch (CouldNotDeleteException $exception) {
                /* @todo add logger here */
                ++$error;
            }
        }
        $deleted = $collectionSize - $error;

        if ($deleted > 0) {
            $this->messageManager->addSuccessMessage(
                __('A total of %1 record(s) have been deleted.', $deleted)
            );
        }

        if ($error > 0) {
            $this->messageManager->addErrorMessage(
                __('A total of %1 record(s) have not been deleted.', $error)
            );
        }

        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $resultRedirect->setPath('hokodo/paymentlog/index');
    }
}
