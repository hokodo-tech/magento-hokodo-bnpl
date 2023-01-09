<?php
/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Hokodo\BNPL\Controller\Adminhtml\Logs;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\Response\Http\FileFactory;
use Magento\Framework\Controller\Result\Raw;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Filesystem;

class Download extends Action implements HttpGetActionInterface
{
    /**
     * @var FileFactory
     */
    private FileFactory $fileFactory;

    /**
     * @var Filesystem
     */
    private Filesystem $filesystem;

    /**
     * @param Context     $context
     * @param FileFactory $fileFactory
     * @param Filesystem  $filesystem
     */
    public function __construct(
        Context $context,
        FileFactory $fileFactory,
        Filesystem $filesystem
    ) {
        parent::__construct($context);
        $this->fileFactory = $fileFactory;
        $this->filesystem = $filesystem;
    }

    /**
     * Checking if ACL is allowed.
     *
     * @return bool
     */
    protected function _isAllowed(): bool
    {
        return $this->_authorization->isAllowed('Hokodo_BNPL::view');
    }

    /**
     * Prepare And Download.
     *
     * @return ResultInterface
     *
     * @throws FileSystemException
     */
    public function execute(): ResultInterface
    {
        $fileName = $this->getRequest()->getParam('file_name');
        $fileSize = $this->getRequest()->getParam('size');
        $directory = $this->filesystem->getDirectoryRead(DirectoryList::LOG);
        if ($directory->isFile($fileName) && $directory->isReadable($fileName)) {
            $content = $directory->readFile($fileName);
        } else {
            $content = __('Requested file %1 does not exists or you have no permissions.', $fileName);
        }
        $this->fileFactory->create(
            $fileName,
            null,
            DirectoryList::VAR_DIR,
            'application/octet-stream',
            $fileSize
        );
        /** @var Raw $resultRaw */
        $resultRaw = $this->resultFactory->create(ResultFactory::TYPE_RAW);
        $resultRaw->setContents($content);
        return $resultRaw;
    }
}
