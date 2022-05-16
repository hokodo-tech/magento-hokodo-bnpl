<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Hokodo\BNPL\Model;

use Hokodo\BNPL\Api\Data\HokodoOrganisationInterface;
use Hokodo\BNPL\Api\Data\OrganisationInterface;
use Hokodo\BNPL\Api\Data\OrganisationInterfaceFactory;
use Hokodo\BNPL\Api\Data\OrganisationUserInterface;
use Hokodo\BNPL\Api\Data\OrganisationUserInterfaceFactory;
use Hokodo\BNPL\Api\Data\PaymentQuoteInterface;
use Hokodo\BNPL\Api\Data\PaymentQuoteInterfaceFactory;
use Hokodo\BNPL\Api\Data\UserInterface;
use Hokodo\BNPL\Api\Data\UserOrganisationInterface;
use Hokodo\BNPL\Api\Data\UserOrganisationInterfaceFactory;
use Hokodo\BNPL\Api\Data\UserOrganisationResultInterfaceFactory;
use Hokodo\BNPL\Api\HokodoOrganisationManagementInterface;
use Hokodo\BNPL\Api\PaymentQuoteRepositoryInterface;
use Hokodo\BNPL\Api\SetOrganisationServiceInterface;
use Hokodo\BNPL\Model\SaveLog as PaymentLogger;
use Hokodo\BNPL\Service\OrganisationService;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\HTTP\Header;

/**
 * Class Hokodo\BNPL\Model\SetOrganisationService.
 */
class SetOrganisationService implements SetOrganisationServiceInterface
{
    /**
     * @var Header
     */
    protected $header;

    /**
     * @var PaymentLogger
     */
    protected $paymentLogger;

    /**
     * @var OrganisationInterfaceFactory
     */
    private $organisationFactory;

    /**
     * @var OrganisationUserInterfaceFactory
     */
    private $organisationUserFactory;

    /**
     * @var PaymentQuoteInterfaceFactory
     */
    private $paymentQuoteFactory;

    /**
     * @var UserOrganisationInterfaceFactory
     */
    private $userOrganisationFactory;

    /**
     * @var UserOrganisationResultInterfaceFactory
     */
    private $resultFactory;

    /**
     * @var HokodoOrganisationManagementInterface
     */
    private $organisationManagement;

    /**
     * @var PaymentQuoteRepositoryInterface
     */
    private $paymentQuoteRepository;

    /**
     * @var OrganisationService
     */
    private $organisationService;

    /**
     * @var DataObjectHelper
     */
    private $dataObjectHelper;

    /**
     * @param Header                                 $header
     * @param OrganisationInterfaceFactory           $organisationFactory
     * @param OrganisationUserInterfaceFactory       $organisationUserFactory
     * @param PaymentQuoteInterfaceFactory           $paymentQuoteFactory
     * @param UserOrganisationInterfaceFactory       $userOrganisationFactory
     * @param UserOrganisationResultInterfaceFactory $resultFactory
     * @param HokodoOrganisationManagementInterface  $organisationManagement
     * @param PaymentQuoteRepositoryInterface        $paymentQuoteRepository
     * @param OrganisationService                    $organisationService
     * @param DataObjectHelper                       $dataObjectHelper
     * @param SaveLog                                $paymentLogger
     */
    public function __construct(
        Header $header,
        OrganisationInterfaceFactory $organisationFactory,
        OrganisationUserInterfaceFactory $organisationUserFactory,
        PaymentQuoteInterfaceFactory $paymentQuoteFactory,
        UserOrganisationInterfaceFactory $userOrganisationFactory,
        UserOrganisationResultInterfaceFactory $resultFactory,
        HokodoOrganisationManagementInterface $organisationManagement,
        PaymentQuoteRepositoryInterface $paymentQuoteRepository,
        OrganisationService $organisationService,
        DataObjectHelper $dataObjectHelper,
        PaymentLogger $paymentLogger
    ) {
        $this->header = $header;
        $this->organisationFactory = $organisationFactory;
        $this->organisationUserFactory = $organisationUserFactory;
        $this->paymentQuoteFactory = $paymentQuoteFactory;
        $this->userOrganisationFactory = $userOrganisationFactory;
        $this->resultFactory = $resultFactory;
        $this->organisationManagement = $organisationManagement;
        $this->paymentQuoteRepository = $paymentQuoteRepository;
        $this->organisationService = $organisationService;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->paymentLogger = $paymentLogger;
    }

    /**
     * @inheritDoc
     *
     * @throws LocalizedException
     *
     * @see \Hokodo\BNPL\Api\SetOrganisationServiceInterface::setOrganisation()
     */
    public function setOrganisation(
        $cartId,
        HokodoOrganisationInterface $organisation,
        UserInterface $user
    ) {
        $result = $this->resultFactory->create();
        $log = [];
        $log[] = '****************** ' . $user->getId() . "\t" . $user->getEmail();
        $log[] = $this->header->getRequestUri();
        try {
            $paymentQuote = $this->getPaymentQuote($cartId);
            $paymentQuote->setUserId($user->getId());
            $createdOrganisation = $this->getCreatedOrganisation($organisation);
            if ($createdOrganisation && $createdOrganisation->getOrganisationId()) {
                $log[] = 'Organisation Id: ' . $createdOrganisation->getOrganisationId();
                $result->setOrganisation($createdOrganisation);
                $paymentQuote->setOrganisationId($createdOrganisation->getApiId());
                $createdOrganisationData = $createdOrganisation->getDataModel();
                $user->setOrganisations([$this->addUserToOrganisation($createdOrganisationData, $user)]);
                $result->setUser($user);
            }

            $this->paymentQuoteRepository->save($paymentQuote);
        } catch (\Exception $e) {
            throw new LocalizedException(
                __($e->getMessage()),
                $e
            );
        }
        $data = [
            'payment_log_content' => $log,
            'action_title' => 'SetOrganisationService::setOrganisation()',
            'status' => 1,
            'quote_id' => $cartId,
        ];
        $this->paymentLogger->execute($data);
        return $result;
    }

    /**
     * A function that catch an exception can't find quotes id.
     *
     * @param int $quoteId
     *
     * @return PaymentQuoteInterface
     */
    private function getPaymentQuote($quoteId)
    {
        try {
            $paymentQuote = $this->paymentQuoteRepository->getByQuoteId($quoteId);
        } catch (\Exception $e) {
            $paymentQuote = $this->paymentQuoteFactory->create();
            $paymentQuote->setQuoteId($quoteId);
        }
        $paymentQuote->resetApiData();
        return $paymentQuote;
    }

    /**
     * A function that gets created organisation.
     *
     * @param HokodoOrganisationInterface $organisation
     *
     * @return HokodoOrganisationInterface
     */
    private function getCreatedOrganisation(HokodoOrganisationInterface $organisation)
    {
        return $this->organisationManagement->getCreatedOrganisation($organisation);
    }

    /**
     * A function that add new user to organisation.
     *
     * @param OrganisationInterface $createdOrganisation
     * @param UserInterface         $user
     *
     * @return UserOrganisationInterface
     *
     * @throws LocalizedException
     */
    private function addUserToOrganisation(OrganisationInterface $createdOrganisation, UserInterface $user)
    {
        $addOrganisationUser = $this->organisationUserFactory->create();
        $addOrganisationUser->setId($user->getId());
        $addOrganisationUser->setRole(OrganisationUserInterface::MEMBER);
        $log = [];
        $log['REQUEST_URI'] = $this->header->getRequestUri();
        $log['CLASS'] = get_class($this->organisationUserFactory);
        $log['DEBUG_BACKTRACE'] = [];
        foreach (debug_backtrace() as $route) {// @codingStandardsIgnoreLine
            if (isset($route['file']) && isset($route['class'])) {
                $log['DEBUG_BACKTRACE'][] = $route['file'] . "\t" . $route['line'];
            }
        }
        $data = [
            'payment_log_content' => $log,
            'action_title' => 'SetOrganisationService::addUserToOrganisation()',
            'status' => 1,
        ];
        $this->paymentLogger->execute($data);
        $organisationUser = $this->organisationService->addUser(
            $createdOrganisation,
            $addOrganisationUser
        );

        if ($organisationUser->getId()) {
            $newUserOrganisation = $this->userOrganisationFactory->create();
            $newUserOrganisation->setId($createdOrganisation->getId());
            $newUserOrganisation->setRole($organisationUser->getRole());

            return $newUserOrganisation;
        }

        return null;
    }
}
