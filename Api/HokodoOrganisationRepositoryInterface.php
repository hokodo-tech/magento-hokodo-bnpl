<?php
/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Api;

use Hokodo\BNPL\Api\Data\HokodoOrganisationInterface;
use Hokodo\BNPL\Api\Data\HokodoOrganisationSearchResultsInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

interface HokodoOrganisationRepositoryInterface
{
    /**
     * A function that gets list.
     *
     * @param SearchCriteriaInterface $searchCriteria
     *
     * @return HokodoOrganisationSearchResultsInterface
     *
     * @throws LocalizedException
     */
    public function getList(SearchCriteriaInterface $searchCriteria);

    /**
     * A function that gets by id.
     *
     * @param int $organisationId
     *
     * @return HokodoOrganisationInterface
     *
     * @throws NoSuchEntityException
     */
    public function getById($organisationId);

    /**
     * A function that gets by company.
     *
     * @param string $companyApiId
     *
     * @return HokodoOrganisationInterface
     *
     * @throws NoSuchEntityException
     */
    public function getByCompany($companyApiId);

    /**
     * A function  that get by api id.
     *
     * @param string $apiId
     *
     * @return HokodoOrganisationInterface
     *
     * @throws NoSuchEntityException
     */
    public function getByApiId($apiId);

    /**
     * A function  that get by api id.
     *
     * @param string $apiId
     *
     * @return HokodoOrganisationInterface
     *
     * @throws NoSuchEntityException
     */
    public function getExistingApiId($apiId);

    /**
     * A function that save.
     *
     * @param HokodoOrganisationInterface $organisation
     *
     * @return HokodoOrganisationInterface
     *
     * @throws CouldNotSaveException
     */
    public function save(Data\HokodoOrganisationInterface $organisation);

    /**
     * A function that delete.
     *
     * @param HokodoOrganisationInterface $organisation
     *
     * @return bool true on success
     *
     * @throws CouldNotDeleteException
     */
    public function delete(Data\HokodoOrganisationInterface $organisation);

    /**
     * A function that delete by id.
     *
     * @param int $organisationId
     *
     * @return bool true on success
     *
     * @throws NoSuchEntityException
     * @throws CouldNotDeleteException
     */
    public function deleteById($organisationId);
}
