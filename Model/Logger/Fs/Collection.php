<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Hokodo\BNPL\Model\Logger\Fs;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Data\Collection\EntityFactoryInterface;
use Magento\Framework\Data\Collection\Filesystem as FilesystemCollection;
use Magento\Framework\Filesystem;
use Magento\Framework\Filesystem\Directory\ReadInterface;
use Magento\Framework\Filesystem\Io\File as IoFilesystem;
use Magento\Framework\Stdlib\DateTime\DateTimeFactory;

class Collection extends FilesystemCollection
{
    /**
     * @var Filesystem
     */
    private Filesystem $filesystem;

    /**
     * @var DateTimeFactory
     */
    private DateTimeFactory $dateTimeFactory;

    /**
     * @var ReadInterface
     */
    private ReadInterface $logDirectory;

    /**
     * @var IoFilesystem
     */
    private IoFilesystem $ioFilesystem;

    /**
     * @param EntityFactoryInterface $entityFactory
     * @param Filesystem             $filesystem
     * @param IoFilesystem           $ioFilesystem
     * @param DateTimeFactory        $dateTimeFactory
     */
    public function __construct(
        EntityFactoryInterface $entityFactory,
        Filesystem $filesystem,
        IoFilesystem $ioFilesystem,
        DateTimeFactory $dateTimeFactory
    ) {
        parent::__construct($entityFactory, $filesystem);
        $this->filesystem = $filesystem;
        $this->ioFilesystem = $ioFilesystem;
        $this->dateTimeFactory = $dateTimeFactory;
    }

    /**
     * Generate Row.
     *
     * @param string $filename
     *
     * @return array
     */
    protected function _generateRow($filename): array
    {
        $this->getLogDirectory();
        $fileInfo = $this->ioFilesystem->getPathInfo($filename);
        $row['file_name'] = $fileInfo['basename'];
        $row['size'] = $this->logDirectory->stat($this->logDirectory->getRelativePath($filename))['size'];
        $mtime = $this->logDirectory->stat($this->logDirectory->getRelativePath($filename))['mtime'];
        $row['updated_at'] = $this->dateTimeFactory->create()->gmtDate(null, $mtime);
        return $row;
    }

    /**
     * Get Log Dir.
     *
     * @return ReadInterface
     */
    public function getLogDirectory(): ReadInterface
    {
        return $this->logDirectory = $this->filesystem->getDirectoryRead(DirectoryList::LOG);
    }

    /**
     * Add Order.
     *
     * @param string $field
     * @param string $direction
     *
     * @return self
     */
    public function addOrder(string $field, string $direction): self
    {
        return $this->setOrder($field, $direction);
    }
}
