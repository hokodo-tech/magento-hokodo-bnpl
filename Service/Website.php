<?php

/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Hokodo\BNPL\Service;

use Magento\Store\Model\StoreManagerInterface;

class Website
{
    /**
     * @var StoreManagerInterface
     */
    private StoreManagerInterface $storeManager;

    /**
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        StoreManagerInterface $storeManager
    ) {
        $this->storeManager = $storeManager;
    }

    /**
     * Initialize website values.
     *
     * @param bool $withDefault
     *
     * @return array
     */
    public function getWebsites(bool $withDefault = false): array
    {
        $websites = [];
        /** @var $website \Magento\Store\Model\Website */
        foreach ($this->storeManager->getWebsites($withDefault) as $website) {
            $websites[$website->getCode()] = $website->getId();
        }
        return $websites;
    }
}
