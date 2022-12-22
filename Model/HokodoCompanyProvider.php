<?php

/**
 * Copyright © 2018-2022 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Hokodo\BNPL\Model;

use Hokodo\BNPL\Api\HokodoEntityResolverInterface;

class HokodoCompanyProvider
{
    /**
     * @var HokodoEntityResolverInterface
     */
    private HokodoEntityResolverInterface $entityResolver;

    /**
     * @var array
     */
    private array $companyProviderTypes;

    /**
     * @param HokodoEntityResolverInterface $entityResolver
     * @param array $companyProviderTypes
     */
    public function __construct(
        HokodoEntityResolverInterface $entityResolver,
        array $companyProviderTypes
    ){
        $this->entityResolver = $entityResolver;
        $this->companyProviderTypes = $companyProviderTypes;
    }

    /**
     * Get Entity where we store Company Id.
     *
     * @return mixed
     */
    public function getHokodoEntity()
    {
        $entityType = $this->entityResolver->getEntityType();
        return $this->getEntityClass($entityType);
    }

    /**
     * Get Entity Class.
     *
     * @param $type
     * @return mixed
     */
    public function getEntityClass($type)
    {
        try {
            return $this->companyProviderTypes[$type];
        } catch (\Exception $e) {
            throw new LocalizedException(__('Data Source "%1" doesn\'t exist', $type), $e);
        }
    }
}