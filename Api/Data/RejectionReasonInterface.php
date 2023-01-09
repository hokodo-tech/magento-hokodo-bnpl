<?php
/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Api\Data;

/**
 * Interface Hokodo\BNPL\Api\Data\RejectionReasonInterface.
 */
interface RejectionReasonInterface extends \Magento\Framework\Api\ExtensibleDataInterface
{
    public const CODE = 'code';
    public const DETAIL = 'detail';
    public const PARAMS = 'params';

    /**
     * A function that sets code.
     *
     * @param string $code
     *
     * @return $this
     */
    public function setCode($code);

    /**
     * A function that gets code.
     *
     * @return string
     */
    public function getCode();

    /**
     * A function that sets detail.
     *
     * @param string $detail
     *
     * @return $this
     */
    public function setDetail($detail);

    /**
     * A function that gets detail.
     *
     * @return string
     */
    public function getDetail();

    /**
     * A function that sets param.
     *
     * @param string[] $params
     *
     * @return $this
     */
    public function setParams(array $params);

    /**
     * A function that gets params.
     *
     * @return string[]
     */
    public function getParams();
}
