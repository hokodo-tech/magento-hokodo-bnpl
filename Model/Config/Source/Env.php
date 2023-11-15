<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Hokodo\BNPL\Model\Config\Source;

use Hokodo\BNPL\Gateway\Config\Config;

class Env
{
    /**
     * @var int[]
     */
    private $map = [
        Config::ENV_DEV => 0,
        Config::ENV_SANDBOX => 1,
    ];

    /**
     * Get environment id.
     *
     * @param string $env
     *
     * @return int
     */
    public function getEnvId(string $env): int
    {
        return $this->map[$env];
    }
}
