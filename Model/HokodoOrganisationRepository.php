<?php
/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Model;

use Hokodo\BNPL\Api\Data\HokodoOrganisationInterface;
use Hokodo\BNPL\Api\Data\HokodoOrganisationInterfaceFactory;
use Hokodo\BNPL\Api\Data\HokodoOrganisationSearchResultsInterfaceFactory;
use Hokodo\BNPL\Api\HokodoOrganisationRepositoryInterface;
use Hokodo\BNPL\Model\ResourceModel\HokodoOrganisation as OrganisationResource;
use Hokodo\BNPL\Model\ResourceModel\HokodoOrganisation\CollectionFactory as OrganisationCollectionFactory;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

class HokodoOrganisationRepository implements HokodoOrganisationRepositoryInterface
{
    /**
     * @var OrganisationResource
     */
    private $resource;

    /**
     * @var HokodoOrganisationInterfaceFactory
     */
    private $organisationFactory;

    /**
     * @var OrganisationCollectionFactory
     */
    private $organisationCollectionFactory;

    /**
     * @var HokodoOrganisationSearchResultsInterfaceFactory
     */
    private $searchResultsFactory;

    /**
     * @var CollectionProcessorInterface
     */
    private $collectionProcessor;

    /**
     * @param OrganisationResource                            $resource
     * @param HokodoOrganisationInterfaceFactory              $organisationFactory
     * @param OrganisationCollectionFactory                   $organisationCollectionFactory
     * @param HokodoOrganisationSearchResultsInterfaceFactory $searchResultsFactory
     * @param CollectionProcessorInterface                    $collectionProcessor
     */
    public function __construct(
        OrganisationResource $resource,
        HokodoOrganisationInterfaceFactory $organisationFactory,
        OrganisationCollectionFactory $organisationCollectionFactory,
        HokodoOrganisationSearchResultsInterfaceFactory $searchResultsFactory,
        CollectionProcessorInterface $collectionProcessor
    ) {
        $this->resource = $resource;
        $this->organisationFactory = $organisationFactory;
        $this->organisationCollectionFactory = $organisationCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->collectionProcessor = $collectionProcessor;
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\HokodoOrganisationRepositoryInterface::getList()
     */
    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        //@todo
        return $this->searchResultsFactory->create();
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\HokodoOrganisationRepositoryInterface::getById()
     */
    public function getById($organisationId)
    {
        /**
         * @var HokodoOrganisationInterface $organisation
         */
        $organisation = $this->organisationFactory->create();
        $this->resource->load($organisation, $organisationId);
        if (!$organisation->getId()) {
            throw new NoSuchEntityException(
                __('The organisation with the "%1" ID doesn\'t exist.', $organisationId)
            );
        }

        return $organisation;
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\HokodoOrganisationRepositoryInterface::getByCompany()
     */
    public function getByCompany($companyApiId)
    {
        /** @var HokodoOrganisationInterface $organisation */
        $organisation = $this->organisationFactory->create();
        $this->resource->load($organisation, $companyApiId, HokodoOrganisationInterface::COMPANY_API_ID);
        if (!$organisation->getId()) {
            throw new NoSuchEntityException(
                __('The organisation with the "%1" company doesn\'t exist.', $companyApiId)
            );
        }
        return $organisation;
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\HokodoOrganisationRepositoryInterface::getByApiId()
     */
    public function getByApiId($apiId)
    {
        /**
         * @var HokodoOrganisationInterface $organisation
         */
        $organisation = $this->organisationFactory->create();
        $this->resource->load($organisation, $apiId, HokodoOrganisationInterface::API_ID);
        if (!$organisation->getId()) {
            throw new NoSuchEntityException(
                __('The organisation with the "%1" api id doesn\'t exist.', $apiId)
            );
        }
        return $organisation;
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\HokodoOrganisationRepositoryInterface::getExistingApiId()
     */
    public function getExistingApiId($apiId)
    {
        /* @var HokodoOrganisationInterface $organisation */
        try {
            $organisation = $this->organisationFactory->create();
            $this->resource->load($organisation, $apiId, HokodoOrganisationInterface::API_ID);
        } catch (NoSuchEntityException $e) {
            $organisation = null;
        }

        return $organisation;
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\HokodoOrganisationRepositoryInterface::save()
     */
    public function save(HokodoOrganisationInterface $organisation)
    {
        try {
            $this->resource->save($organisation);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__($exception->getMessage()));
        }
        return $organisation;
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\HokodoOrganisationRepositoryInterface::delete()
     */
    public function delete(HokodoOrganisationInterface $organisation)
    {
        try {
            $this->resource->delete($organisation);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__($exception->getMessage()));
        }

        return true;
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\HokodoOrganisationRepositoryInterface::deleteById()
     */
    public function deleteById($organisationId)
    {
        return $this->delete($this->getById($organisationId));
    }
}
