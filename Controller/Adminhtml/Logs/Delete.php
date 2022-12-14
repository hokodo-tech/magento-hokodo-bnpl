<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Hokodo\BNPL\Controller\Adminhtml\Logs;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Filesystem;

class Delete extends Action implements HttpPostActionInterface
{
    /**
     * @var Filesystem
     */
    private Filesystem $filesystem;

    /**
     * @param Context    $context
     * @param Filesystem $filesystem
     */
    public function __construct(
        Context $context,
        Filesystem $filesystem
    ) {
        parent::__construct($context);
        $this->filesystem = $filesystem;
    }

    /**
     * Checking if ACL is allowed.
     *
     * @return bool
     */
    protected function _isAllowed(): bool
    {
        return $this->_authorization->isAllowed('Hokodo_BNPL::delete');
    }

    /**
     * Delete file.
     *
     * @return ResultInterface
     *
     * @throws FileSystemException
     */
    public function execute(): ResultInterface
    {
        $result = false;
        $fileName = $this->getRequest()->getParam('file_name');
        $directory = $this->filesystem->getDirectoryWrite(DirectoryList::LOG);
        if ($directory->isFile($fileName) && $directory->isWritable($fileName)) {
            try {
                $result = $directory->delete($fileName);
            } catch (FileSystemException $exception) {
                $this->messageManager->addErrorMessage($exception->getMessage());
            }
        }

        if ($result) {
            $this->messageManager->addSuccessMessage('Log File %1 has been deleted', $fileName);
        } else {
            $this->messageManager->addErrorMessage('Log File %1 has not been deleted', $fileName);
        }

        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $resultRedirect->setPath('hokodo/logs/index');
    }
}
