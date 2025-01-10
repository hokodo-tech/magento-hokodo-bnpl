<?php

/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Hokodo\BNPL\Api\Data;

interface OrderDocumentInterface
{
    /**
     * String constants for property names.
     */
    public const ID = 'id';
    public const ORDER_ID = 'order_id';
    public const DOCUMENT_ID = 'document_id';
    public const DOCUMENT_TYPE = 'document_type';

    public const TYPE_INVOICE = 'invoice';
    public const TYPE_SHIPMENT = 'shipment';
    public const TYPE_CREDIT_MEMO = 'credit_note';

    /**
     * Getter for Id.
     *
     * @return int|null
     */
    public function getId(): ?int;

    /**
     * Setter for Id.
     *
     * @param int|null $id
     *
     * @return self
     */
    public function setId(?int $id): self;

    /**
     * Getter for OrderId.
     *
     * @return int|null
     */
    public function getOrderId(): ?int;

    /**
     * Setter for OrderId.
     *
     * @param int|null $orderId
     *
     * @return self
     */
    public function setOrderId(?int $orderId): self;

    /**
     * Getter for DocumentId.
     *
     * @return string|null
     */
    public function getDocumentId(): ?string;

    /**
     * Setter for DocumentId.
     *
     * @param string|null $documentId
     *
     * @return self
     */
    public function setDocumentId(?string $documentId): self;

    /**
     * Getter for DocumentType.
     *
     * @return string|null
     */
    public function getDocumentType(): ?string;

    /**
     * Setter for DocumentType.
     *
     * @param string|null $documentType
     *
     * @return self
     */
    public function setDocumentType(?string $documentType): self;
}
