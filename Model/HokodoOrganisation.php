<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Model;

use Hokodo\BNPL\Api\Data\HokodoOrganisationInterface;
use Hokodo\BNPL\Api\Data\OrganisationInterface;
use Hokodo\BNPL\Api\Data\OrganisationInterfaceFactory;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Registry;
use Magento\Framework\Stdlib\DateTime\DateTimeFactory;

/**
 * Class Hokodo\BNPL\Model\HokodoOrganisation.
 */
class HokodoOrganisation extends AbstractModel implements HokodoOrganisationInterface
{
    /**
     * @var DateTimeFactory
     */
    private $dateFactory;

    /**
     * @var OrganisationInterfaceFactory
     */
    private $organisationFactory;

    /**
     * @param Context                      $context
     * @param Registry                     $registry
     * @param DateTimeFactory              $dateFactory
     * @param OrganisationInterfaceFactory $organisationFactory
     * @param AbstractResource             $resource
     * @param AbstractDb                   $resourceCollection
     * @param array                        $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        DateTimeFactory $dateFactory,
        OrganisationInterfaceFactory $organisationFactory,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct(
            $context,
            $registry,
            $resource,
            $resourceCollection,
            $data
        );
        $this->organisationFactory = $organisationFactory;
        $this->dateFactory = $dateFactory;
    }

    /**
     * @inheritDoc
     *
     * @see \Magento\Framework\Model\AbstractModel::_construct()
     */
    protected function _construct()
    {
        $this->_init(\Hokodo\BNPL\Model\ResourceModel\HokodoOrganisation::class);
    }

    /**
     * @inheritDoc
     *
     * @see \Magento\Framework\Model\AbstractModel::beforeSave()
     */
    public function beforeSave()
    {
        if (!$this->getId()) {
            $this->setCreatedAt($this->dateFactory->create()->gmtDate());
        }

        return parent::beforeSave();
    }

    /**
     * @inheritDoc
     *
     * @see \Magento\Framework\Model\AbstractModel::setId()
     */
    public function setId($id)
    {
        return $this->setOrganisationId($id);
    }

    /**
     * @inheritDoc
     *
     * @see \Magento\Framework\Model\AbstractModel::getId()
     */
    public function getId()
    {
        return $this->getOrganisationId();
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\HokodoOrganisationInterface::setOrganisationId()
     */
    public function setOrganisationId($organisationId)
    {
        return $this->setData(self::ORGANISATION_ID, $organisationId);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\HokodoOrganisationInterface::getOrganisationId()
     */
    public function getOrganisationId()
    {
        return $this->getData(self::ORGANISATION_ID);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\HokodoOrganisationInterface::setApiId()
     */
    public function setApiId($apiId)
    {
        return $this->setData(self::API_ID, $apiId);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\HokodoOrganisationInterface::getApiId()
     */
    public function getApiId()
    {
        return $this->getData(self::API_ID);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\HokodoOrganisationInterface::setCountry()
     */
    public function setCountry($country)
    {
        return $this->setData(self::COUNTRY, $country);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\HokodoOrganisationInterface::getCountry()
     */
    public function getCountry()
    {
        return $this->getData(self::COUNTRY);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\HokodoOrganisationInterface::setOrganizationName()
     */
    public function setName($name)
    {
        return $this->setData(self::NAME, $name);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\HokodoOrganisationInterface::getName()
     */
    public function getName()
    {
        return $this->getData(self::NAME);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\HokodoOrganisationInterface::setAddress()
     */
    public function setAddress($address)
    {
        return $this->setData(self::ADDRESS, $address);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\HokodoOrganisationInterface::getAddress()
     */
    public function getAddress()
    {
        return $this->getData(self::ADDRESS);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\HokodoOrganisationInterface::setCity()
     */
    public function setCity($city)
    {
        return $this->setData(self::CITY, $city);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\HokodoOrganisationInterface::getCity()
     */
    public function getCity()
    {
        return $this->getData(self::CITY);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\HokodoOrganisationInterface::setPostcode()
     */
    public function setPostcode($postcode)
    {
        return $this->setData(self::POSTCODE, $postcode);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\HokodoOrganisationInterface::getPostcode()
     */
    public function getPostcode()
    {
        return $this->getData(self::POSTCODE);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\HokodoOrganisationInterface::setEmail()
     */
    public function setEmail($email)
    {
        return $this->setData(self::EMAIL, $email);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\HokodoOrganisationInterface::getEmail()
     */
    public function getEmail()
    {
        return $this->getData(self::EMAIL);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\HokodoOrganisationInterface::setPhone()
     */
    public function setPhone($phone)
    {
        return $this->setData(self::PHONE, $phone);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\HokodoOrganisationInterface::getPhone()
     */
    public function getPhone()
    {
        return $this->getData(self::PHONE);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\HokodoOrganisationInterface::setCompanyApiId()
     */
    public function setCompanyApiId($companyApiId)
    {
        return $this->setData(self::COMPANY_API_ID, $companyApiId);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\HokodoOrganisationInterface::getCompanyApiId()
     */
    public function getCompanyApiId()
    {
        return $this->getData(self::COMPANY_API_ID);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\HokodoOrganisationInterface::setCreatedAt()
     */
    public function setCreatedAt($createdAt)
    {
        return $this->setData(self::CREATED_AT, $createdAt);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\HokodoOrganisationInterface::getCreatedAt()
     */
    public function getCreatedAt()
    {
        return $this->getData(self::CREATED_AT);
    }

    /**
     * A function that gets data model.
     *
     * @return \Hokodo\BNPL\Api\Data\OrganisationInterface
     */
    public function getDataModel()
    {
        /**
         * @var OrganisationInterface $organisation
         */
        $organisation = $this->organisationFactory->create();

        $organisation->setCompany($this->getCompanyApiId());
        $organisation->setId($this->getApiId());
        $organisation->setRegistered($this->getCreatedAt());
        $organisation->setUniqueId($this->getOrganisationId());

        return $organisation;
    }

    /**
     * Returns array.
     *
     * @return array
     */
    public function __toArray()// @codingStandardsIgnoreLine
    {
        $data = $this->_data;
        $hasToArray = function ($model) {
            return is_object($model) && method_exists($model, '__toArray') && is_callable([$model, '__toArray']);
        };
        foreach ($data as $key => $value) {
            if ($hasToArray($value)) {
                $data[$key] = $value->__toArray();
            } elseif (is_array($value)) {
                foreach ($value as $nestedKey => $nestedValue) {
                    if ($hasToArray($nestedValue)) {
                        $value[$nestedKey] = $nestedValue->__toArray();
                    }
                }
                $data[$key] = $value;
            }
        }
        return $data;
    }
}
