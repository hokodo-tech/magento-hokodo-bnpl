<?php

/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Hokodo\BNPL\Api\Data;

interface HokodoCustomerInterface extends HokodoEntityInterface
{
    public const ID = 'id';
    public const CUSTOMER_ID = 'customer_id';
    public const COMPANY_ID = 'company_id';
    public const USER_ID = 'user_id';
    public const ORGANISATION_ID = 'organisation_id';
    public const CREDIT_LIMIT = 'credit_limit';

    /**
     * Id getter.
     *
     * @return int
     */
    public function getId(): ?int;

    /**
     * Id setter.
     *
     * @param int $id
     *
     * @return $this
     */
    public function setId(int $id): self;

    /**
     * Customer Id getter.
     *
     * @return int|null
     */
    public function getCustomerId(): ?int;

    /**
     * Customer Id setter.
     *
     * @param int $customerId
     *
     * @return $this
     */
    public function setCustomerId(int $customerId): self;

    /**
     * User Id getter.
     *
     * @return string|null
     */
    public function getUserId(): ?string;

    /**
     * User Id setter.
     *
     * @param string|null $userId
     *
     * @return $this
     */
    public function setUserId(?string $userId): self;

    /**
     * Organisation Id getter.
     *
     * @return string|null
     */
    public function getOrganisationId(): ?string;

    /**
     * Organisation Id setter.
     *
     * @param string|null $organisationId
     *
     * @return $this
     */
    public function setOrganisationId(?string $organisationId): self;
}
