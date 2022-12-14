<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Model\Data;

use Hokodo\BNPL\Api\Data\UserInterface;
use Magento\Framework\Api\AbstractSimpleObject;
use Magento\Framework\HTTP\Header;
use Psr\Log\LoggerInterface as Logger;

/**
 * Class Hokodo\BNPL\Model\Data\User.
 */
class User extends AbstractSimpleObject implements UserInterface
{
    /**
     * @var Header
     */
    protected $header;

    /**
     * @var Logger
     */
    protected $logger;

    /**
     * @param Header $header
     * @param Logger $logger
     */
    public function __construct(
        Header $header,
        Logger $logger
    ) {
        $this->header = $header;
        $this->logger = $logger;
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\UserInterface::setId()
     */
    public function setId($id)
    {
        return $this->setData(self::ID, $id);
    }

    /**
     * A function that sets data.
     *
     * @param string $field
     * @param string $value
     *
     * @return mixed
     *
     * @throws \Exception
     */
    public function setData($field, $value)
    {
        if ($field == self::ID) {
            $log = [];
            $log['REQUEST_URI'] = "*******************************   \t" .
                $this->header->getRequestUri() . "\t" . $value . "\t";
            $log['debug_backtrace'] = [];
            foreach (debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS) as $key => $route) {
                if (isset($route['file']) && isset($route['class'])) {
                    $log['debug_backtrace'][] = $route['file'] . "\t" . $route['line'];
                }
            }
            $log['_COOKIE'] = print_r($_COOKIE, true); // @codingStandardsIgnoreLine
            $log['_ENV'] = print_r($_ENV, true); // @codingStandardsIgnoreLine
            $data = [
                'payment_log_content' => $log,
                'action_title' => 'Hokodo\BNPL\Model\Data\User::setData()',
                'status' => 1,
            ];
            $this->logger->debug(__METHOD__, $data);
        }

        return parent::setData($field, $value);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\UserInterface::getId()
     */
    public function getId()
    {
        return $this->_get(self::ID);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\UserInterface::setEmail()
     */
    public function setEmail($email)
    {
        return $this->setData(self::EMAIL, $email);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\UserInterface::getEmail()
     */
    public function getEmail()
    {
        return $this->_get(self::EMAIL);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\UserInterface::setEmailValidated()
     */
    public function setEmailValidated($emailValidated)
    {
        return $this->setData(self::EMAIL_VALIDATED, $emailValidated);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\UserInterface::getEmailValidated()
     */
    public function getEmailValidated()
    {
        return $this->_get(self::EMAIL_VALIDATED);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\UserInterface::setUniqueId()
     */
    public function setUniqueId($uniqueId)
    {
        return $this->setData(self::UNIQUE_ID, $uniqueId);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\UserInterface::getUniqueId()
     */
    public function getUniqueId()
    {
        return $this->_get(self::UNIQUE_ID);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\UserInterface::setName()
     */
    public function setName($name)
    {
        return $this->setData(self::NAME, $name);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\UserInterface::getName()
     */
    public function getName()
    {
        return $this->_get(self::NAME);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\UserInterface::setPhone()
     */
    public function setPhone($phone)
    {
        return $this->setData(self::PHONE, $phone);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\UserInterface::getPhone()
     */
    public function getPhone()
    {
        return $this->_get(self::PHONE);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\UserInterface::setRegistered()
     */
    public function setRegistered($registered)
    {
        return $this->setData(self::REGISTERED, $registered);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\UserInterface::getRegistered()
     */
    public function getRegistered()
    {
        return $this->_get(self::REGISTERED);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\UserInterface::setOrganisations()
     */
    public function setOrganisations(array $organisations)
    {
        return $this->setData(self::ORGANISATIONS, $organisations);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\UserInterface::getOrganisations()
     */
    public function getOrganisations()
    {
        return $this->_get(self::ORGANISATIONS);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\UserInterface::setType()
     */
    public function setType($type)
    {
        return $this->setData(self::TYPE, $type);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\Data\UserInterface::getType()
     */
    public function getType()
    {
        return $this->_get(self::TYPE);
    }
}
