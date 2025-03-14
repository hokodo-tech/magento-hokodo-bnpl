<?php

/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Api\Data;

/**
 * Interface Hokodo\BNPL\Api\Data\PaymentMethodInterface.
 */
interface PaymentMethodInterface extends \Magento\Framework\Api\ExtensibleDataInterface
{
    public const TYPE = 'type';

    /**
     * A function that sets type.
     *
     * @param string $type
     *
     * @return $this
     */
    public function setType($type);

    /**
     * A function that gets type.
     *
     * @return string
     */
    public function getType();
}
