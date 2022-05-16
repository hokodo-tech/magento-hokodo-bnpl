<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Api\Data;

/**
 * Interface Hokodo\BNPL\Api\Data\UserInterface.
 */
interface UserInterface extends \Magento\Framework\Api\ExtensibleDataInterface
{
    public const ID = 'id';
    public const EMAIL = 'email';
    public const EMAIL_VALIDATED = 'email_validated';
    public const UNIQUE_ID = 'unique_id';
    public const NAME = 'name';
    public const PHONE = 'phone';
    public const REGISTERED = 'registered';
    public const ORGANISATIONS = 'organisations';
    public const TYPE = 'type';

    /**
     * A function that sets id.
     *
     * @param string $id
     *
     * @return $this
     */
    public function setId($id);

    /**
     * A function that id.
     *
     * @return string
     */
    public function getId();

    /**
     * A function that sets email.
     *
     * @param string $email
     *
     * @return $this
     */
    public function setEmail($email);

    /**
     * A function that gets email.
     *
     * @return string
     */
    public function getEmail();

    /**
     * A function that sets email validated.
     *
     * @param bool $emailValidated
     *
     * @return $this
     */
    public function setEmailValidated($emailValidated);

    /**
     * A function that gets email validated.
     *
     * @return bool
     */
    public function getEmailValidated();

    /**
     * A function that sets unique id.
     *
     * @param string $uniqueId
     *
     * @return $this
     */
    public function setUniqueId($uniqueId);

    /**
     * A function that gets unique id.
     *
     * @return string
     */
    public function getUniqueId();

    /**
     * A function that sets name.
     *
     * @param string $name
     *
     * @return $this
     */
    public function setName($name);

    /**
     * A function that gets name.
     *
     * @return string
     */
    public function getName();

    /**
     * A function that sets phone.
     *
     * @param string $phone
     *
     * @return $this
     */
    public function setPhone($phone);

    /**
     * A function that gets phone.
     *
     * @return string
     */
    public function getPhone();

    /**
     * A function that sets registered.
     *
     * @param string $registered
     *
     * @return $this
     */
    public function setRegistered($registered);

    /**
     * A function that gets registered.
     *
     * @return string
     */
    public function getRegistered();

    /**
     * A function that sets organisations.
     *
     * @param \Hokodo\BNPL\Api\Data\UserOrganisationInterface[] $organisations
     *
     * @return $this
     */
    public function setOrganisations(array $organisations);

    /**
     * A function that gets organisations.
     *
     * @return \Hokodo\BNPL\Api\Data\UserOrganisationInterface[]
     */
    public function getOrganisations();

    /**
     * A function that sets type.
     *
     * @param string $type
     *
     * @return $this
     */
    public function setType($type);

    /**
     * A function that gets type.
     *
     * @return string
     */
    public function getType();
}
