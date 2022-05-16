<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Api\Data;

/**
 * Interface Hokodo\BNPL\Api\Data\OrderDocumentsInterface.
 */
interface OrderDocumentsInterface
{
    public const DOCUMENT_ID = 'id';
    public const DOCUMENT_ORDER = 'order';
    public const DOCUMENT_TYPE = 'doc_type';
    public const DOCUMENT_AMOUNT = 'amount';
    public const DOCUMENT_DESCRIPTION = 'description';
    public const DOCUMENT_METADATA = 'metadata';
    public const DOCUMENT_FILE = 'file';

    /**
     * A function that sets document id.
     *
     * @param string $docId
     *
     * @return $this
     */
    public function setId($docId);

    /**
     * A function that gets document id.
     *
     * @return string
     */
    public function getId();

    /**
     * A function that sets document order.
     *
     * @param string $order
     *
     * @return $this
     */
    public function setOrder($order);

    /**
     * A function that gets document order.
     *
     * @return string
     */
    public function getOrder();

    /**
     * A function that sets document type.
     *
     * @param string $docType
     *
     * @return $this
     */
    public function setDocType($docType);

    /**
     * A function that gets quantity.
     *
     * @return string
     */
    public function getDocType();

    /**
     * A function that set amount.
     *
     * @param string $amount
     *
     * @return $this
     */
    public function setAmount($amount);

    /**
     * A function that get amount.
     *
     * @return string
     */
    public function getAmount();

    /**
     * A function that set document description.
     *
     * @param string $description
     *
     * @return $this
     */
    public function setDescription($description);

    /**
     * A function that get document description.
     *
     * @return string
     */
    public function getDescription();

    /**
     * A function that set meta data.
     *
     * @param string $metaData
     *
     * @return $this
     */
    public function setMetaData($metaData);

    /**
     * A function that get meta data.
     *
     * @return string
     */
    public function getMetaData();

    /**
     * A function that set file path.
     *
     * @param string $filePath
     *
     * @return $this
     */
    public function setFilePath($filePath);

    /**
     * A function that get file path.
     *
     * @return string
     */
    public function getFilePath();
}
