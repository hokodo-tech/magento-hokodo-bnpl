<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Hokodo\BNPL\Api\Data\Webapi;

interface CreateOrganisationResponseInterface
{
    public const ORGANISATION_ID = 'id';

    /**
     * @param string $id
     *
     * @return mixed
     */
    public function setId(string $id);

    /**
     * @return string
     */
    public function getId(): string;
}
