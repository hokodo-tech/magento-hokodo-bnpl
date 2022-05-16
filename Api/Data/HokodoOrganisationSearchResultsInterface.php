<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

/**
 * Interface Hokodo\BNPL\Api\Data\HokodoOrganisationSearchResultsInterface.
 */
interface HokodoOrganisationSearchResultsInterface extends SearchResultsInterface
{
    /**
     * A function that set items.
     *
     * @param \Hokodo\BNPL\Api\Data\HokodoOrganisationInterface[] $items
     *
     * @return $this
     */
    public function setItems(array $items);

    /**
     * A function that gets items.
     *
     * @return \Hokodo\BNPL\Api\Data\HokodoOrganisationInterface[]
     */
    public function getItems();
}
