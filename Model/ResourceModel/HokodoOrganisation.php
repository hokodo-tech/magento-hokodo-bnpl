<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Model\ResourceModel;

use Hokodo\BNPL\Api\Data\HokodoOrganisationInterface;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Class Hokodo\BNPL\Model\ResourceModel\HokodoOrganisation.
 */
class HokodoOrganisation extends AbstractDb
{
    public const TABLE_NAME_ORGANISATION = 'hokodo_organisation';

    /**
     * @inheritDoc
     *
     * @see \Magento\Framework\Model\ResourceModel\AbstractResource::_construct()
     */
    protected function _construct()
    {
        $this->_init(self::TABLE_NAME_ORGANISATION, HokodoOrganisationInterface::ORGANISATION_ID);
    }
}
