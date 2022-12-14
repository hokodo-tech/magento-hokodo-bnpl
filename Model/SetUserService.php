<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Model;

use Hokodo\BNPL\Api\Data\PaymentQuoteInterfaceFactory;
use Hokodo\BNPL\Api\Data\UserInterface;
use Hokodo\BNPL\Api\Data\UserOrganisationResultInterface;
use Hokodo\BNPL\Api\Data\UserOrganisationResultInterfaceFactory;
use Hokodo\BNPL\Api\HokodoOrganisationManagementInterface;
use Hokodo\BNPL\Api\PaymentQuoteRepositoryInterface;
use Hokodo\BNPL\Api\SetUserServiceInterface;
use Hokodo\BNPL\Service\UserService;
use Magento\Framework\Exception\LocalizedException;
use Psr\Log\LoggerInterface as Logger;

/**
 * Class Hokodo\BNPL\Model\SetUserService.
 */
class SetUserService implements SetUserServiceInterface
{
    /**
     * @var PaymentQuoteInterfaceFactory
     */
    private PaymentQuoteInterfaceFactory $paymentQuoteFactory;

    /**
     * @var UserOrganisationResultInterfaceFactory
     */
    private UserOrganisationResultInterfaceFactory $resultFactory;

    /**
     * @var HokodoOrganisationManagementInterface
     */
    private HokodoOrganisationManagementInterface $organisationManagement;

    /**
     * @var PaymentQuoteRepositoryInterface
     */
    private PaymentQuoteRepositoryInterface $paymentQuoteRepository;

    /**
     * @var UserService
     */
    private UserService $userService;

    /**
     * @var Logger
     */
    private Logger $logger;

    /**
     * @param PaymentQuoteInterfaceFactory           $paymentQuoteFactory
     * @param UserOrganisationResultInterfaceFactory $resultFactory
     * @param HokodoOrganisationManagementInterface  $organisationManagement
     * @param PaymentQuoteRepositoryInterface        $paymentQuoteRepository
     * @param UserService                            $userService
     * @param Logger                                 $logger
     */
    public function __construct(
        PaymentQuoteInterfaceFactory $paymentQuoteFactory,
        UserOrganisationResultInterfaceFactory $resultFactory,
        HokodoOrganisationManagementInterface $organisationManagement,
        PaymentQuoteRepositoryInterface $paymentQuoteRepository,
        UserService $userService,
        Logger $logger
    ) {
        $this->paymentQuoteFactory = $paymentQuoteFactory;
        $this->resultFactory = $resultFactory;
        $this->organisationManagement = $organisationManagement;
        $this->paymentQuoteRepository = $paymentQuoteRepository;
        $this->userService = $userService;
        $this->logger = $logger;
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\SetUserServiceInterface::setUser()
     */
    public function setUser($cartId, UserInterface $user)
    {
        /**
         * @var UserOrganisationResultInterface $result
         */
        $result = $this->resultFactory->create();

        try {
            $paymentQuote = $this->getPaymentQuote($cartId);

            /**
             * @var \Hokodo\BNPL\Api\Data\UserInterface $userResult
             */
            $userResult = $this->userService->create($user);

            $paymentQuote->setUserId($userResult->getId());

            $result->setUser($userResult);

            $organisation = $this->getOrganisation($userResult);
            if ($organisation) {
                $result->setOrganisation($organisation);
                $paymentQuote->setOrganisationId($organisation->getApiId());
            }

            $this->paymentQuoteRepository->save($paymentQuote);
        } catch (\Exception $e) {
            $data = [
                'payment_log_content' => __($e->getMessage()),
                'action_title' => 'SetUserService:: setUser',
                'status' => 0,
                'quote_id' => $cartId,
            ];
            $this->logger->error(__METHOD__, $data);
            throw new LocalizedException(
                //__('An error occurred on the server. Please try to place the order again.'),
                __($e->getMessage()),
                $e
            );
        }
        return $result;
    }

    /**
     * A function that returns organisation interface.
     *
     * @param UserInterface $user
     *
     * @return \Hokodo\BNPL\Api\Data\HokodoOrganisationInterface
     */
    private function getOrganisation(UserInterface $user)
    {
        $organisations = $user->getOrganisations();
        if (is_array($organisations)) {
            foreach ($organisations as $userOrganisation) {
                if ($userOrganisation->getId()) {
                    $organisation = $this->organisationManagement->getUserOrganisation(
                        $userOrganisation->getId()
                    );
                    if ($organisation && $organisation->getOrganisationId()) {
                        return $organisation;
                    }
                }
            }
        }
        return null;
    }

    /**
     * A function that returns payment interface.
     *
     * @param int $quoteId
     *
     * @return \Hokodo\BNPL\Api\Data\PaymentQuoteInterface
     */
    private function getPaymentQuote($quoteId)
    {
        try {
            /**
             * @var \Hokodo\BNPL\Api\Data\PaymentQuoteInterface $paymentQuote
             */
            $paymentQuote = $this->paymentQuoteRepository->getByQuoteId($quoteId);
        } catch (\Exception $e) {
            $data = [
                'payment_log_content' => __($e->getMessage()),
                'action_title' => 'SetUserService:: getPaymentQuote',
                'status' => 0,
                'quote_id' => $quoteId,
            ];
            $this->logger->error(__METHOD__, $data);
            $paymentQuote = $this->paymentQuoteFactory->create();
            $paymentQuote->setQuoteId($quoteId);
        }

        $paymentQuote->resetApiData();
        return $paymentQuote;
    }
}
