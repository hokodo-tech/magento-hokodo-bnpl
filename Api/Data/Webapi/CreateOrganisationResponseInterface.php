<?php

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
