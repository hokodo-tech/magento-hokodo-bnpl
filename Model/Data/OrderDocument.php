<?php
/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Hokodo\BNPL\Model\Data;

use Hokodo\BNPL\Api\Data\OrderDocumentInterface;
use Magento\Framework\DataObject;

class OrderDocument extends DataObject implements OrderDocumentInterface
{
    /**
     * Getter for Id.
     *
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->getData(self::ID) === null ? null
            : (int) $this->getData(self::ID);
    }

    /**
     * Setter for Id.
     *
     * @param int|null $id
     *
     * @return $this
     */
    public function setId(?int $id): self
    {
        $this->setData(self::ID, $id);
        return $this;
    }

    /**
     * Getter for OrderId.
     *
     * @return int|null
     */
    public function getOrderId(): ?int
    {
        return $this->getData(self::ORDER_ID) === null ? null
            : (int) $this->getData(self::ORDER_ID);
    }

    /**
     * Setter for OrderId.
     *
     * @param int|null $orderId
     *
     * @return $this
     */
    public function setOrderId(?int $orderId): self
    {
        $this->setData(self::ORDER_ID, $orderId);
        return $this;
    }

    /**
     * Getter for DocumentId.
     *
     * @return string|null
     */
    public function getDocumentId(): ?string
    {
        return $this->getData(self::DOCUMENT_ID);
    }

    /**
     * Setter for DocumentId.
     *
     * @param string|null $documentId
     *
     * @return $this
     */
    public function setDocumentId(?string $documentId): self
    {
        $this->setData(self::DOCUMENT_ID, $documentId);
        return $this;
    }

    /**
     * Getter for DocumentType.
     *
     * @return string|null
     */
    public function getDocumentType(): ?string
    {
        return $this->getData(self::DOCUMENT_TYPE);
    }

    /**
     * Setter for DocumentType.
     *
     * @param string|null $documentType
     *
     * @return $this
     */
    public function setDocumentType(?string $documentType): self
    {
        $this->setData(self::DOCUMENT_TYPE, $documentType);
        return $this;
    }
}
