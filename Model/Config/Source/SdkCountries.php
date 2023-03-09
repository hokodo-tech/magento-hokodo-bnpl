<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Hokodo\BNPL\Model\Config\Source;

class SdkCountries implements \Magento\Framework\Data\OptionSourceInterface
{
    /**
     * @var string[]
     */
    private $countries = ['GB', 'ES', 'FR', 'DE', 'NL', 'BE'];

    /**
     * @inheritDoc
     */
    public function toOptionArray()
    {
        $options = [];
        foreach ($this->countries as $country) {
            $options[] = [
                'value' => $country,
                'label' => $country,
            ];
        }

        return $options;
    }
}
