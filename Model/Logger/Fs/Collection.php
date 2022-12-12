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
     * @param EntityFactoryInterface $entityFactory
     * @param Filesystem $filesystem
     * @param DateTimeFactory $dateTimeFactory
     */
    public function __construct(
        EntityFactoryInterface $entityFactory,
        Filesystem $filesystem,
        DateTimeFactory $dateTimeFactory
    ) {
        parent::__construct($entityFactory, $filesystem);
        $this->filesystem = $filesystem;
        $this->dateTimeFactory = $dateTimeFactory;
    }

    /**
     * Generate Row.
     *
     * @param $filename
     * @return array
     */
    protected function _generateRow($filename): array
    {
        $this->getLogDirectory();
        $row['file_name'] = basename($filename);
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
     * @param $field
     * @param $direction
     * @return self
     */
    public function addOrder($field, $direction): self
    {
        return $this->setOrder($field, $direction);
    }
}
