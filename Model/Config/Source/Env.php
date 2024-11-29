<?php

/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Hokodo\BNPL\Model\Config\Source;

use Hokodo\BNPL\Gateway\Config\Config;

class Env implements \Magento\Framework\Data\OptionSourceInterface
{
    /**
     * @var int[]
     */
    private $map = [
        Config::ENV_DEV => 0,
        Config::ENV_SANDBOX => 1,
        Config::ENV_PRODUCTION => 2,
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

    /**
     * @inheritDoc
     */
    public function toOptionArray()
    {
        $options = [];
        foreach ([1 => __('Yes'), 0 => __('No')] as $boolValue => $boolLabel) {
            foreach ($this->map as $key => $value) {
                $options[] = [
                    'label' => ucfirst(sprintf('%s %s', $key, $boolLabel)),
                    'value' => sprintf('%s_%s', $key, $boolValue),
                ];
            }
        }

        return $options;
    }

    /**
     * Get map for env.
     *
     * @return int[]
     */
    public function getEnvMap(): array
    {
        return $this->map;
    }
}
