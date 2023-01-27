<?php

/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Hokodo\BNPL\Model;

use Hokodo\BNPL\Api\HokodoEntityRepositoryInterface;
use Hokodo\BNPL\Api\HokodoEntityTypeResolverInterface;

class HokodoCompanyProvider
{
    /**
     * @var HokodoEntityTypeResolverInterface
     */
    private HokodoEntityTypeResolverInterface $entityTypeResolver;

    /**
     * @var array
     */
    private array $companyProviderTypes;

    /**
     * @param HokodoEntityTypeResolverInterface $entityResolver
     * @param array                             $companyProviderTypes
     */
    public function __construct(
        HokodoEntityTypeResolverInterface $entityResolver,
        array $companyProviderTypes
    ) {
        $this->entityTypeResolver = $entityResolver;
        $this->companyProviderTypes = $companyProviderTypes;
    }

    /**
     * Get Entity's (where we store Company Id) Repository.
     *
     * @return HokodoEntityRepositoryInterface
     */
    public function getEntityRepository(): HokodoEntityRepositoryInterface
    {
        $entityType = $this->entityTypeResolver->resolve();
        return $this->getEntityRepositoryClass($entityType);
    }

    /**
     * Get Entity Class.
     *
     * @param string $type
     *
     * @return HokodoEntityRepositoryInterface
     */
    public function getEntityRepositoryClass(string $type): HokodoEntityRepositoryInterface
    {
        try {
            return $this->companyProviderTypes[$type];
        } catch (\Exception $e) {
            throw new LocalizedException(__('Data Source "%1" doesn\'t exist', $type), $e);
        }
    }
}
