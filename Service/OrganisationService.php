<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Service;

use Hokodo\BNPL\Api\Data\OrganisationInterface;
use Hokodo\BNPL\Api\Data\OrganisationUserInterface;

/**
 * Class Hokodo\BNPL\Service\OrganisationService.
 */
class OrganisationService extends AbstractService
{
    /**
     * A function that create organisation.
     *
     * @param OrganisationInterface $organisation
     *
     * @return OrganisationInterface
     */
    public function create(OrganisationInterface $organisation)
    {
        return $this->executeCommand('organisation_create', [
            'organisations' => $organisation,
        ])->getDataModel();
    }

    /**
     * A function that returns view of organisation.
     *
     * @param OrganisationInterface $organisation
     *
     * @return OrganisationInterface
     */
    public function get(OrganisationInterface $organisation)
    {
        return $this->executeCommand('organisation_view', [
            'organisations' => $organisation,
        ])->getDataModel();
    }

    /**
     * A function that returns list of organisations.
     *
     * @return OrganisationInterface[]
     */
    public function getList()
    {
        return $this->executeCommand('organisation_list', [])->getList();
    }

    /**
     * A function that removes organisation.
     *
     * @param OrganisationInterface $organisation
     *
     * @return \Magento\Payment\Gateway\Command\ResultInterface
     */
    public function delete(OrganisationInterface $organisation)
    {
        return $this->executeCommand('organisation_delete', [
            'organisations' => $organisation,
        ]);
    }

    /**
     * A function that add user to organisation.
     *
     * @param OrganisationInterface     $organisation
     * @param OrganisationUserInterface $user
     *
     * @return \Hokodo\BNPL\Api\Data\OrganisationUserInterface
     */
    public function addUser(OrganisationInterface $organisation, OrganisationUserInterface $user)
    {
        return $this->executeCommand('organisation_add_user', [
            'organisations' => $organisation,
            'user_organisation' => $user,
        ])->getDataModel();
    }

    /**
     * A function that removes a user from organisation.
     *
     * @param OrganisationInterface     $organisation
     * @param OrganisationUserInterface $user
     *
     * @return \Hokodo\BNPL\Gateway\Command\Result\Result
     */
    public function removeUser(OrganisationInterface $organisation, OrganisationUserInterface $user)
    {
        return $this->executeCommand('organisation_remove_user', [
            'organisations' => $organisation,
            'user_organisation' => $user,
        ]);
    }
}
