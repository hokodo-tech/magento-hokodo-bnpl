<?php

/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Hokodo\BNPL\Service;

class LogObfuscatorService
{
    /**
     * A function that execute logs.
     *
     * @param string $data
     *
     * @return array|string|string[]|null
     */
    public function execute(string $data)
    {
        if (!str_contains($data, 'Token ')) {
            return $data;
        }
        return preg_replace('/Token \w*/', 'Token OBFUSCATED ', $data);
    }
}
