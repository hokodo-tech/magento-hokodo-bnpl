<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Model\Data;

use Hokodo\BNPL\Api\Data\OrderDocumentsInterface;
use Magento\Framework\Api\AbstractSimpleObject;

/**
 * Class Hokodo\BNPL\Model\Data\OrderDocumentsInfo.
 */
class OrderDocumentsInfo extends AbstractSimpleObject implements OrderDocumentsInterface
{
    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderDocumentsInterface::setId()
     */
    public function setId($docId)
    {
        return $this->setData(self::DOCUMENT_ID, $docId);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderDocumentsInterface::getId()
     */
    public function getId()
    {
        return $this->_get(self::DOCUMENT_ID);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderDocumentsInterface::setOrder()
     */
    public function setOrder($order)
    {
        return $this->setData(self::DOCUMENT_ORDER, $order);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderDocumentsInterface::getOrder()
     */
    public function getOrder()
    {
        return $this->_get(self::DOCUMENT_ORDER);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderDocumentsInterface::setDocType()
     */
    public function setDocType($docType)
    {
        return $this->setData(self::DOCUMENT_TYPE, $docType);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderDocumentsInterface::getDocType()
     */
    public function getDocType()
    {
        return $this->_get(self::DOCUMENT_TYPE);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderDocumentsInterface::setAmount()
     */
    public function setAmount($amount)
    {
        return $this->setData(self::DOCUMENT_AMOUNT, $amount);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderDocumentsInterface::getAmount()
     */
    public function getAmount()
    {
        return $this->_get(self::DOCUMENT_AMOUNT);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderDocumentsInterface::setDescription()
     */
    public function setDescription($description)
    {
        return $this->setData(self::DOCUMENT_DESCRIPTION, $description);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderDocumentsInterface::getDescription()
     */
    public function getDescription()
    {
        return $this->_get(self::DOCUMENT_DESCRIPTION);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderDocumentsInterface::setMetaData()
     */
    public function setMetaData($metaData)
    {
        return $this->setData(self::DOCUMENT_METADATA, $metaData);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderDocumentsInterface::getMetaData()
     */
    public function getMetaData()
    {
        return $this->_get(self::DOCUMENT_METADATA);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderDocumentsInterface::getFilePath()
     */
    public function getFilePath()
    {
        return $this->_get(self::DOCUMENT_FILE);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\OrderDocumentsInterface::setFilePath()
     */
    public function setFilePath($filePath)
    {
        return $this->setData(self::DOCUMENT_FILE, $filePath);
    }
}
