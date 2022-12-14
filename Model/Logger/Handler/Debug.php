<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Hokodo\BNPL\Model\Logger\Handler;

use Hokodo\BNPL\Gateway\Config\Config;
use Magento\Framework\Filesystem\DriverInterface;
use Magento\Framework\Logger\Handler\Base;
use Monolog\Logger;

class Debug extends Base
{
    /**
     * @var int
     */
    protected $loggerType = Logger::DEBUG;

    /**
     * @var Config
     */
    private Config $config;

    /**
     * @param Config          $config
     * @param DriverInterface $filesystem
     * @param string|null     $filePath
     * @param string|null     $fileName
     */
    public function __construct(
        Config $config,
        DriverInterface $filesystem,
        ?string $filePath = null,
        ?string $fileName = null
    ) {
        parent::__construct($filesystem, $filePath, $fileName);
        $this->config = $config;
    }

    /**
     * Write log.
     *
     * @param array $record
     *
     * @return void
     */
    public function write(array $record): void
    {
        $isDebugEnabled = $this->config->isDebugEnabled();
        if ($isDebugEnabled) {
            parent::write($record);
        }
    }
}
