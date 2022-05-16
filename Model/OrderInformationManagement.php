<?php

/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Model;

use Hokodo\BNPL\Api\Data\HokodoOrganisationInterface;
use Hokodo\BNPL\Api\Data\OrderInformationInterface;
use Hokodo\BNPL\Api\Data\OrganisationInterface;
use Hokodo\BNPL\Api\Data\OrganisationUserInterface;
use Hokodo\BNPL\Api\Data\PaymentOffersInterface;
use Hokodo\BNPL\Api\Data\PaymentOffersInterfaceFactory;
use Hokodo\BNPL\Api\Data\PaymentQuoteInterface;
use Hokodo\BNPL\Api\Data\PaymentQuoteInterfaceFactory;
use Hokodo\BNPL\Api\Data\UserInterface;
use Hokodo\BNPL\Api\HokodoOrganisationRepositoryInterface;
use Hokodo\BNPL\Api\OrderInformationManagementInterface;
use Hokodo\BNPL\Api\PaymentQuoteRepositoryInterface;
use Hokodo\BNPL\Model\SaveLog as PaymentLogger;
use Hokodo\BNPL\Service\OrderService;
use Hokodo\BNPL\Service\OrganisationServiceFactory;
use Hokodo\BNPL\Service\PaymentOffersService;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Quote\Api\CartRepositoryInterface;

/**
 * Class Hokodo\BNPL\Model\OrderInformationManagement.
 */
class OrderInformationManagement implements OrderInformationManagementInterface
{
    /**
     * @var PaymentLogger
     */
    protected $paymentLogger;
    /**
     * @var PaymentOffersInterfaceFactory
     */
    private $paymentOffersFactory;
    /**
     * @var PaymentQuoteInterfaceFactory
     */
    private $paymentQuoteFactory;
    /**
     * @var PaymentQuoteRepositoryInterface
     */
    private $paymentQuoteRepository;
    /**
     * @var OrderService
     */
    private $orderService;
    /**
     * @var PaymentOffersService
     */
    private $paymentOffersService;
    /**
     * @var CartRepositoryInterface
     */
    private $cartRepository;

    /**
     * @var OrganisationServiceFactory
     */
    private $organisationServiceFactory;

    /**
     * @var OrganisationUserInterface
     */
    private $organisationUserInterface;

    /**
     * @var OrganisationInterface
     */
    private $organisationInterface;

    /**
     * @var HokodoOrganisationRepositoryInterface
     */
    private $organisationRepository;

    /**
     * @var ResourceConnection
     */
    private $resourceConnection;

    /**
     * @param PaymentOffersInterfaceFactory         $paymentOffersFactory
     * @param PaymentQuoteInterfaceFactory          $paymentQuoteFactory
     * @param PaymentQuoteRepositoryInterface       $paymentQuoteRepository
     * @param OrderService                          $orderService
     * @param PaymentOffersService                  $paymentOffersService
     * @param CartRepositoryInterface               $cartRepository
     * @param OrganisationServiceFactory            $organisationServiceFactory
     * @param OrganisationUserInterface             $organisationUserInterface
     * @param OrganisationInterface                 $organisationInterface
     * @param HokodoOrganisationRepositoryInterface $organisationRepository
     * @param PaymentLogger                         $paymentLogger
     * @param ResourceConnection                    $resourceConnection
     */
    public function __construct(
        PaymentOffersInterfaceFactory $paymentOffersFactory,
        PaymentQuoteInterfaceFactory $paymentQuoteFactory,
        PaymentQuoteRepositoryInterface $paymentQuoteRepository,
        OrderService $orderService,
        PaymentOffersService $paymentOffersService,
        CartRepositoryInterface $cartRepository,
        OrganisationServiceFactory $organisationServiceFactory,
        OrganisationUserInterface $organisationUserInterface,
        OrganisationInterface $organisationInterface,
        HokodoOrganisationRepositoryInterface $organisationRepository,
        PaymentLogger $paymentLogger,
        ResourceConnection $resourceConnection
    ) {
        $this->paymentOffersFactory = $paymentOffersFactory;
        $this->paymentQuoteFactory = $paymentQuoteFactory;
        $this->paymentQuoteRepository = $paymentQuoteRepository;
        $this->orderService = $orderService;
        $this->paymentOffersService = $paymentOffersService;
        $this->cartRepository = $cartRepository;
        $this->organisationServiceFactory = $organisationServiceFactory;
        $this->organisationUserInterface = $organisationUserInterface;
        $this->organisationInterface = $organisationInterface;
        $this->organisationRepository = $organisationRepository;
        $this->paymentLogger = $paymentLogger;
        $this->resourceConnection = $resourceConnection;
    }

    /**
     * @inheritDoc
     *
     * @throws CouldNotSaveException
     *
     * @see \Hokodo\BNPL\Api\OrderInformationManagementInterface::setHokodoOrder()
     */
    public function setOrder($cartId, UserInterface $user, HokodoOrganisationInterface $organisation)
    {
        try {
            $paymentQuote = $this->getPaymentQuoteByQuoteId($cartId);
            if (!$paymentQuote->getUserId()) {
                $paymentQuote->setUserId($user->getId());
            }
            if (!$paymentQuote->getOrganisationId()) {
                $paymentQuote->setOrganisationId($organisation->getApiId());
            }
            $quote = $this->cartRepository->getActive($cartId);
            $orgExist = $this->organisationRepository->getExistingApiId($organisation->getApiId());
            if ($quote->getCustomerIsGuest() || !$orgExist) {
                /** @var OrganisationUserInterface */
                $organisationUser = $this->organisationUserInterface;
                $organisationUser->setId($user->getId());
                $organisationUser->setEmail($user->getEmail());
                $organisationUser->setRole(OrganisationUserInterface::MEMBER);
                /** @var OrganisationInterface */
                $createdOrganisation = $this->organisationInterface->setId($organisation->getApiId());
                $this->organisationServiceFactory->create()->addUser($createdOrganisation, $organisationUser);
            }

            $result = $this->orderService->create($quote, $user, $organisation->getDataModel());
            if ($result->getId()) {
                $paymentQuote->setOrderId($result->getId());
            }
            if ($result->getPaymentOffer()) {
                $paymentQuote->setOfferId($result->getPaymentOffer());
            }
            $this->paymentQuoteRepository->save($paymentQuote);
        } catch (LocalizedException $e) {
            $data = [
                'payment_log_content' => 'Exception: ' . $e->getMessage(),
                'action_title' => 'OrderInformationManagement::setOrder() LocalizedException',
                'status' => 0,
                'quote_id' => $cartId,
            ];
            $this->paymentLogger->execute($data);
            throw new CouldNotSaveException(
                __($e->getMessage()),
                $e
            );
        } catch (\Exception $e) {
            $data = [
                'payment_log_content' => 'Exception: ' . $e->getMessage(),
                'action_title' => 'OrderInformationManagement::setOrder() Exception',
                'status' => 0,
                'quote_id' => $cartId,
            ];
            $this->paymentLogger->execute($data);
            throw new CouldNotSaveException(
                __($e->getMessage()),
                $e
            );
        }
        return $result;
    }

    /**
     * A function gets payment quote interface.
     *
     * @param int $quoteId
     *
     * @return PaymentQuoteInterface
     */
    private function getPaymentQuoteByQuoteId($quoteId)
    {
        try {
            $paymentQuote = $this->paymentQuoteRepository->getByQuoteId($quoteId);
        } catch (\Exception $e) {
            $paymentQuote = $this->paymentQuoteFactory->create();
            $paymentQuote->setQuoteId($quoteId);
            $data = [
                'payment_log_content' => 'Exception: ' . $e->getMessage(),
                'action_title' => 'OrderInformationManagement::getPaymentQuoteByQuoteId() Exception',
                'status' => 0,
                'quote_id' => $quoteId,
            ];
            $this->paymentLogger->execute($data);
        }

        return $paymentQuote;
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\OrderInformationManagementInterface::requestNewOffer()
     */
    public function requestNewOffer($cartId, OrderInformationInterface $order, $customerId)
    {
        try {
            $result = $this->translatePaymentPlan(
                $this->paymentOffersService->requestNew($order, $cartId, $customerId)
            );
            if ($result->getId()) {
                $paymentQuote = $this->getPaymentQuoteByOrderId($order->getId());
                $paymentQuote->setOfferId($result->getId());
                $this->paymentQuoteRepository->save($paymentQuote);
            }
        } catch (LocalizedException $e) {
            $log = [];
            $log[] = 'A' . $cartId;
            $s = 'UPDATE hokodo_payment_quote set order_id=NULL,' // @codingStandardsIgnoreLine
                . "organisation_id=NULL where order_id='" . $order->getId() . "'";
            $log[] = 'Order ID: ' . $cartId;
            $log[] = 'Update SQL query: ' . $s; // @codingStandardsIgnoreLine
            $log[] = 'Exception: ' . $e->getMessage();
            $data = [
                'payment_log_content' => $log,
                'action_title' => 'OrderInformationManagement::requestNewOffer() LocalizedException',
                'status' => 0,
                'quote_id' => $cartId,
            ];
            $this->paymentLogger->execute($data);

            $this->resourceConnection->getConnection()->query($s); // @codingStandardsIgnoreLine
            throw new CouldNotSaveException(
                __($e->getMessage()),
                $e
            );
        } catch (\Exception $e) {
            $data = [
                'payment_log_content' => 'Exception: ' . $e->getMessage(),
                'action_title' => 'OrderInformationManagement::requestNewOffer() Exception',
                'status' => 0,
                'quote_id' => $cartId,
            ];
            $this->paymentLogger->execute($data);

            throw new CouldNotSaveException(
                __($e->getMessage()),
                $e
            );
        }

        return $result;
    }

    /**
     * A function that translates payment plan.
     *
     * @param PaymentOffersInterface $paymentOffers
     *
     * @return PaymentOffersInterface
     */
    private function translatePaymentPlan(PaymentOffersInterface $paymentOffers): PaymentOffersInterface
    {
        $translators = [
            'en' => ['14d' => 'Pay in 14 days', '30d' => 'Pay in 30 days', '30dEOM' => 'Pay in 30 days end of month',
                '60d' => 'Pay in 60 days', '60dEOM' => 'Pay in 60 days end of month',
                'pay3x' => 'Pay in three instalments', ],
        ];

        $plans = [];
        foreach ($paymentOffers->getOfferedPaymentPlans() as $pindex => $plan) {
            $planname = str_replace(' ', '', trim($plan->getName()));
            if (isset($translators['en'][$planname])) {
                $plan->setName($translators['en'][$planname]);
            }
            $plans[] = $plan;
        }
        $paymentOffers->setOfferedPaymentPlans($plans);
        return $paymentOffers;
    }

    /**
     * A function gets payment quote by order.
     *
     * @param string $orderId
     *
     * @return PaymentQuoteInterface
     *
     * @throws NoSuchEntityException
     */
    private function getPaymentQuoteByOrderId($orderId)
    {
        return $this->paymentQuoteRepository->getByOrderId($orderId);
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Api\OrderInformationManagementInterface::getPaymentOffer()
     */
    public function getPaymentOffer($offerId, $cartId)
    {
        try {
            /**
             * @var PaymentOffersInterface $paymentOffers
             */
            $paymentOffers = $this->paymentOffersFactory->create();
            $paymentOffers->setId($offerId);

            /**
             * PaymentOfferInterface variable.
             *
             * @var PaymentOffersInterface $result
             */
            $result = $this->translatePaymentPlan($this->paymentOffersService->get($paymentOffers));
        } catch (LocalizedException $e) {
            $data = [
                'payment_log_content' => 'Exception: ' . $e->getMessage(),
                'action_title' => 'OrderInformationManagement::getPaymentOffer() LocalizedException',
                'status' => 0,
                'quote_id' => $cartId,
            ];
            $this->paymentLogger->execute($data);
            throw new CouldNotSaveException(
                __($e->getMessage()),
                $e
            );
        } catch (\Exception $e) {
            $data = [
                'payment_log_content' => 'Exception: ' . $e->getMessage(),
                'action_title' => 'OrderInformationManagement::getPaymentOffer() Exception',
                'status' => 0,
                'quote_id' => $cartId,
            ];
            $this->paymentLogger->execute($data);
            throw new CouldNotSaveException(
                __($e->getMessage()),
                $e
            );
        }
        return $result;
    }
}
