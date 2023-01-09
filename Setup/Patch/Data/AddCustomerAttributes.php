<?php
/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Setup\Patch\Data;

use Magento\Customer\Model\Customer;
use Magento\Customer\Setup\CustomerSetupFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchVersionInterface;

/**
 * Class Hokodo\BNPL\Setup\Patch\Data\AddCustomerAttributes.
 */
class AddCustomerAttributes implements DataPatchInterface, PatchVersionInterface
{
    /**
     * @var ModuleDataSetupInterface
     */
    private $moduleDataSetup;

    /**
     * @var CustomerSetupFactory
     */
    private $customerSetupFactory;

    /**
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param CustomerSetupFactory     $customerSetupFactory
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        CustomerSetupFactory $customerSetupFactory
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->customerSetupFactory = $customerSetupFactory;
    }

    /**
     * @inheritDoc
     *
     * @see \Magento\Framework\Setup\Patch\PatchInterface::apply()
     */
    public function apply()
    {
        /**
         * @var \Magento\Customer\Setup\CustomerSetup $customerSetup
         */
        $customerSetup = $this->customerSetupFactory->create(['setup' => $this->moduleDataSetup]);
        $hokodoUserIdAttribute = $customerSetup->getAttribute(Customer::ENTITY, 'hokodo_user_id');
        if (empty($hokodoUserIdAttribute)) {
            $customerSetup->addAttribute(
                Customer::ENTITY,
                'hokodo_user_id',
                [
                    'type' => 'varchar',
                    'label' => 'Hokodo User Id',
                    'input' => 'text',
                    'position' => 100,
                    'required' => false,
                    'visible' => false,
                    'user_defined' => false,
                    'system' => true,
                    'group' => 'General',
                ]
            );
        }
        $hokodoUserIdAttribute = $customerSetup->getEavConfig()->getAttribute(Customer::ENTITY, 'hokodo_user_id');
        $hokodoUserIdAttribute->setData(
            'used_in_forms',
            ['adminhtml_customer']
        );
        $hokodoUserIdAttribute->setData(
            'is_used_in_grid',
            false
        );
        $hokodoUserIdAttribute->setData(
            'is_visible_in_grid',
            false
        );
        $hokodoUserIdAttribute->setData(
            'is_filterable_in_grid',
            false
        );
        $hokodoUserIdAttribute->setData(
            'is_searchable_in_grid',
            false
        );
        $hokodoUserIdAttribute->save();

        /**
         * @var \Magento\Customer\Setup\CustomerSetup $customerSetup
         */
        $customerSetup = $this->customerSetupFactory->create(['setup' => $this->moduleDataSetup]);
        $hokodoOrganizationIdAttribute = $customerSetup->getAttribute(Customer::ENTITY, 'hokodo_organization_id');
        if (empty($hokodoOrganizationIdAttribute)) {
            $customerSetup->addAttribute(
                Customer::ENTITY,
                'hokodo_organization_id',
                [
                    'type' => 'int',
                    'label' => 'Hokodo Organization Id',
                    'input' => 'text',
                    'position' => 100,
                    'required' => false,
                    'visible' => false,
                    'user_defined' => false,
                    'system' => true,
                    'group' => 'General',
                ]
            );
        }
        $hokodoOrganizationIdAttribute = $customerSetup->getEavConfig()->getAttribute(
            Customer::ENTITY,
            'hokodo_user_id'
        );
        $hokodoOrganizationIdAttribute->setData(
            'used_in_forms',
            ['adminhtml_customer']
        );
        $hokodoOrganizationIdAttribute->setData(
            'is_used_in_grid',
            false
        );
        $hokodoOrganizationIdAttribute->setData(
            'is_visible_in_grid',
            false
        );
        $hokodoOrganizationIdAttribute->setData(
            'is_filterable_in_grid',
            false
        );
        $hokodoOrganizationIdAttribute->setData(
            'is_searchable_in_grid',
            false
        );
        $hokodoOrganizationIdAttribute->save();
    }

    /**
     * @inheritDoc
     *
     * @see \Magento\Framework\Setup\Patch\PatchInterface::getAliases()
     */
    public function getAliases()
    {
        return [];
    }

    /**
     * @inheritDoc
     *
     * @see \Magento\Framework\Setup\Patch\DependentPatchInterface::getDependencies()
     */
    public static function getDependencies()
    {
        return [];
    }

    /**
     * @inheritDoc
     *
     * @see \Magento\Framework\Setup\Patch\PatchVersionInterface::getVersion()
     */
    public static function getVersion()
    {
        return '1.0.0';
    }
}
