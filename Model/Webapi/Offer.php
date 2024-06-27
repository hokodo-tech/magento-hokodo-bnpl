<?php
/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Hokodo\BNPL\Model\Webapi;

use Hokodo\BNPL\Api\Data\HokodoCustomerInterface;
use Hokodo\BNPL\Api\Data\HokodoQuoteInterface;
use Hokodo\BNPL\Api\Data\OrganisationInterface;
use Hokodo\BNPL\Api\Data\PaymentOffersInterface;
use Hokodo\BNPL\Api\Data\PaymentPlanInterface;
use Hokodo\BNPL\Api\Data\UserInterface;
use Hokodo\BNPL\Api\Data\Webapi\OfferRequestInterface;
use Hokodo\BNPL\Api\Data\Webapi\OfferResponseInterface;
use Hokodo\BNPL\Api\Data\Webapi\OfferResponseInterfaceFactory;
use Hokodo\BNPL\Api\HokodoCustomerRepositoryInterface;
use Hokodo\BNPL\Api\HokodoQuoteRepositoryInterface;
use Hokodo\BNPL\Api\Webapi\OfferInterface;
use Hokodo\BNPL\Gateway\Config\Config;
use Hokodo\BNPL\Gateway\Service\Offer as OfferGatewayService;
use Hokodo\BNPL\Gateway\Service\Order as OrderGatewayService;
use Hokodo\BNPL\Gateway\Service\Organisation as OrganisationService;
use Hokodo\BNPL\Gateway\Service\User as UserService;
use Hokodo\BNPL\Model\CompanyCreditService;
use Hokodo\BNPL\Model\RequestBuilder\OfferBuilder;
use Hokodo\BNPL\Model\RequestBuilder\OrderBuilder;
use Hokodo\BNPL\Model\RequestBuilder\OrganisationBuilder;
use Hokodo\BNPL\Model\RequestBuilder\UserBuilder;
use Magento\Checkout\Model\Session;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\NotFoundException;
use Magento\Framework\Phrase;
use Magento\Framework\Webapi\Exception;
use Magento\Payment\Gateway\Command\CommandException;
use Magento\Quote\Api\CartTotalRepositoryInterface;
use Psr\Log\LoggerInterface;

class Offer implements OfferInterface
{
    /**
     * @var HokodoQuoteInterface|null
     */
    private ?HokodoQuoteInterface $hokodoQuote;

    /**
     * @var HokodoCustomerInterface|null
     */
    private ?HokodoCustomerInterface $hokodoCustomer;

    /**
     * @var OfferResponseInterfaceFactory
     */
    private OfferResponseInterfaceFactory $responseInterfaceFactory;

    /**
     * @var OrderGatewayService
     */
    private OrderGatewayService $orderGatewayService;

    /**
     * @var OfferGatewayService
     */
    private OfferGatewayService $offerGatewayService;

    /**
     * @var Session
     */
    private Session $checkoutSession;

    /**
     * @var HokodoQuoteRepositoryInterface
     */
    private HokodoQuoteRepositoryInterface $hokodoQuoteRepository;

    /**
     * @var OrderBuilder
     */
    private OrderBuilder $orderBuilder;

    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * @var OrganisationBuilder
     */
    private OrganisationBuilder $organisationBuilder;

    /**
     * @var OrganisationService
     */
    private OrganisationService $organisationService;

    /**
     * @var UserBuilder
     */
    private UserBuilder $userBuilder;

    /**
     * @var UserService
     */
    private UserService $userService;

    /**
     * @var OfferBuilder
     */
    private OfferBuilder $offerBuilder;

    /**
     * @var HokodoCustomerRepositoryInterface
     */
    private HokodoCustomerRepositoryInterface $hokodoCustomerRepository;

    /**
     * @var Config
     */
    private Config $config;

    /**
     * @var CartTotalRepositoryInterface
     */
    private CartTotalRepositoryInterface $cartTotalRepository;

    /**
     * @var \Hokodo\BNPL\Model\CompanyCreditService
     */
    private CompanyCreditService $companyCredit;

    /**
     * @param OfferResponseInterfaceFactory           $responseInterfaceFactory
     * @param OrderGatewayService                     $orderGatewayService
     * @param OfferGatewayService                     $offerGatewayService
     * @param Session                                 $checkoutSession
     * @param HokodoQuoteRepositoryInterface          $hokodoQuoteRepository
     * @param OrderBuilder                            $orderBuilder
     * @param LoggerInterface                         $logger
     * @param OrganisationBuilder                     $organisationBuilder
     * @param OrganisationService                     $organisationService
     * @param UserBuilder                             $userBuilder
     * @param UserService                             $userService
     * @param OfferBuilder                            $offerBuilder
     * @param HokodoCustomerRepositoryInterface       $hokodoCustomerRepository
     * @param Config                                  $config
     * @param CartTotalRepositoryInterface            $cartTotalRepository
     * @param \Hokodo\BNPL\Model\CompanyCreditService $companyCredit
     */
    public function __construct(
        OfferResponseInterfaceFactory $responseInterfaceFactory,
        OrderGatewayService $orderGatewayService,
        OfferGatewayService $offerGatewayService,
        Session $checkoutSession,
        HokodoQuoteRepositoryInterface $hokodoQuoteRepository,
        OrderBuilder $orderBuilder,
        LoggerInterface $logger,
        OrganisationBuilder $organisationBuilder,
        OrganisationService $organisationService,
        UserBuilder $userBuilder,
        UserService $userService,
        OfferBuilder $offerBuilder,
        HokodoCustomerRepositoryInterface $hokodoCustomerRepository,
        Config $config,
        CartTotalRepositoryInterface $cartTotalRepository,
        CompanyCreditService $companyCredit
    ) {
        $this->responseInterfaceFactory = $responseInterfaceFactory;
        $this->orderGatewayService = $orderGatewayService;
        $this->offerGatewayService = $offerGatewayService;
        $this->checkoutSession = $checkoutSession;
        $this->hokodoQuoteRepository = $hokodoQuoteRepository;
        $this->orderBuilder = $orderBuilder;
        $this->logger = $logger;
        $this->organisationBuilder = $organisationBuilder;
        $this->organisationService = $organisationService;
        $this->userBuilder = $userBuilder;
        $this->userService = $userService;
        $this->offerBuilder = $offerBuilder;
        $this->hokodoCustomerRepository = $hokodoCustomerRepository;
        $this->hokodoCustomer = null;
        $this->config = $config;
        $this->cartTotalRepository = $cartTotalRepository;
        $this->companyCredit = $companyCredit;
    }

    /**
     * Request new offer method.
     *
     * @param OfferRequestInterface $payload
     *
     * @return OfferResponseInterface
     *
     * @throws CommandException
     * @throws Exception
     * @throws NoSuchEntityException
     * @throws NotFoundException
     * @throws LocalizedException
     */
    public function requestNew(OfferRequestInterface $payload): OfferResponseInterface
    {
        $this->hokodoQuote = $this->hokodoQuoteRepository->getByQuoteId($this->checkoutSession->getQuoteId());
        $this->hokodoCustomer = $this->hokodoCustomerRepository->getByCustomerId(
            (int) $this->checkoutSession->getQuote()->getCustomerId()
        );

        if (!$this->hokodoQuote->getOrderId() || $this->isDiffersCompanyId($payload->getCompanyId())) {
            $this->syncHokodoCustomerAndQuote($payload->getCompanyId());
            if (!$this->hokodoQuote->getOrganisationId()) {
                $this->assignOrganisation();
            }
            if (!$this->hokodoQuote->getUserId()) {
                $this->assignUser();
            }
            $this->hokodoCustomerRepository->save($this->hokodoCustomer);
            $this->createOrder();
        } elseif ($this->hokodoQuote->getPatchType() !== null && !$this->patchOrder()) {
            $this->createOrder();
        }

        return $this->responseInterfaceFactory->create()->setOffer($this->getOffer());
    }

    /**
     * Request new offer method for guest user.
     *
     * @param OfferRequestInterface $payload
     *
     * @return OfferResponseInterface
     *
     * @throws Exception
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function guestRequestNew(OfferRequestInterface $payload): OfferResponseInterface
    {
        $this->hokodoQuote = $this->hokodoQuoteRepository->getByQuoteId($this->checkoutSession->getQuoteId());

        if (!$this->hokodoQuote->getOrderId() || $this->isDiffersCompanyId($payload->getCompanyId())) {
            $this->hokodoQuote
                ->setQuoteId((int) $this->checkoutSession->getQuoteId())
                ->setCompanyId($payload->getCompanyId());
            $this->assignOrganisation();
            $this->assignUser();
            $this->createOrder();
        } elseif ($this->hokodoQuote->getPatchType() !== null && !$this->patchOrder()) {
            $this->createOrder();
        }
        return $this->responseInterfaceFactory->create()->setOffer($this->getOffer());
    }

    /**
     * Check if company id provided is different across Hokodo customer and offer.
     *
     * @param string $companyId
     *
     * @return bool
     */
    private function isDiffersCompanyId(string $companyId): bool
    {
        return $companyId !== $this->hokodoQuote->getCompanyId() ||
            $companyId !== ($this->hokodoCustomer ? $this->hokodoCustomer->getCompanyId() : $companyId);
    }

    /**
     * Sync Hokodo customer and quote data before calling API.
     *
     * @param string $companyId
     *
     * @return void
     *
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    private function syncHokodoCustomerAndQuote(string $companyId): void
    {
        $customerCompanyId = $this->hokodoCustomer->getCompanyId();

        if (!$this->hokodoQuote->getQuoteId()) {
            $this->hokodoQuote->setQuoteId((int) $this->checkoutSession->getQuote()->getId());
        }

        if ($customerCompanyId && $customerCompanyId === $companyId) {
            $this->hokodoQuote
                ->setCompanyId($companyId)
                ->setOrganisationId($this->hokodoCustomer->getOrganisationId())
                ->setUserId($this->hokodoCustomer->getUserId());
        } else {
            $this->hokodoCustomer
                ->setCustomerId((int) $this->checkoutSession->getQuote()->getCustomerId())
                ->setCompanyId($companyId)
                ->setCreditLimit($this->companyCredit->getCreditLimit($companyId))
                ->setOrganisationId('')
                ->setUserId('');
            $this->hokodoQuote
                ->setCompanyId($companyId)
                ->setOrganisationId('')
                ->setUserId('')
                ->setOrderId('');
        }
    }

    /**
     * Create organisation and assign it to customer.
     *
     * @throws Exception
     */
    private function assignOrganisation(): void
    {
        $organisation = $this->createOrganisation($this->hokodoQuote->getCompanyId());
        $this->hokodoQuote->setOrganisationId($organisation->getId());
        if ($this->hokodoCustomer) {
            $this->hokodoCustomer->setOrganisationId($organisation->getId());
        }
    }

    /**
     * Create hokodo organisation request.
     *
     * @param string $companyId
     *
     * @return OrganisationInterface
     *
     * @throws Exception
     */
    private function createOrganisation(string $companyId): OrganisationInterface
    {
        try {
            $organisation = $this->organisationService->createOrganisation(
                $this->organisationBuilder->build(
                    $companyId,
                    $this->getUserEmail(),
                    !$this->checkoutSession->getQuote()->getCustomer()
                )
            );
            if ($dataModel = $organisation->getDataModel()) {
                return $dataModel;
            }

            throw new NotFoundException(__('No organisation found in API response'));
        } catch (\Exception $e) {
            $data = [
                'message' => 'Hokodo_BNPL: createOrganisation call failed with error',
                'error' => $e->getMessage(),
            ];
            $this->logger->error(__METHOD__, $data);
            throw new Exception(
                __('There was an error during payment method set up. Please reload the page or try again later.')
            );
        }
    }

    /**
     * Get user email for logged in and guest.
     *
     * @return string
     *
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    private function getUserEmail(): string
    {
        return $this->checkoutSession->getQuote()->getCustomerEmail()
            ?: $this->checkoutSession->getQuote()->getBillingAddress()->getEmail();
    }

    /**
     * Create user and assign it to customer.
     *
     * @throws NoSuchEntityException
     * @throws LocalizedException
     * @throws Exception
     */
    private function assignUser(): void
    {
        $user = $this->createUser(
            $this->hokodoQuote->getOrganisationId()
        );
        $this->hokodoQuote->setUserId($user->getId());
        if ($this->hokodoCustomer) {
            $this->hokodoCustomer->setUserId($user->getId());
        }
    }

    /**
     * Create hokodo user request.
     *
     * @param string $organisationId
     *
     * @return UserInterface
     *
     * @throws Exception
     */
    private function createUser(string $organisationId): UserInterface
    {
        try {
            $user = $this->userService->createUser(
                $this->userBuilder->build($this->getCustomerDataObject(), $organisationId)
            );
            if ($dataModel = $user->getDataModel()) {
                return $dataModel;
            }

            throw new NotFoundException(__('No user found in API response'));
        } catch (\Exception $e) {
            $data = [
                'message' => 'Hokodo_BNPL: createUser call failed with error',
                'error' => $e->getMessage(),
            ];
            $this->logger->error(__METHOD__, $data);
            throw new Exception(
                __('There was an error during payment method set up. Please reload the page or try again later.')
            );
        }
    }

    /**
     * Get customer object from quote or from billing address.
     *
     * @return CustomerInterface
     *
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    private function getCustomerDataObject(): CustomerInterface
    {
        $customer = $this->checkoutSession->getQuote()->getCustomer();
        if (!$customer->getId()) {
            $billingAddress = $this->checkoutSession->getQuote()->getBillingAddress();
            $customer
                ->setEmail($billingAddress->getEmail())
                ->setFirstname($billingAddress->getFirstname())
                ->setLastname($billingAddress->getLastname());
        }

        return $customer;
    }

    /**
     * Create hokodo order request.
     *
     * @return void
     *
     * @throws Exception
     */
    private function createOrder(): void
    {
        try {
            $orderRequest = $this->orderBuilder->buildOrderRequestBase($this->checkoutSession->getQuote());
            $orderRequest->setCustomer(
                $this->orderBuilder->buildCustomer($this->checkoutSession->getQuote())
                    ->setUser($this->hokodoQuote->getUserId())
                    ->setOrganisation($this->hokodoQuote->getOrganisationId())
            );
            if ($this->config->getValue(Config::TOTALS_FIX)) {
                $items[] = $this->orderBuilder->buildTotalItem($this->checkoutSession->getQuote());
            } else {
                $items = $this->orderBuilder->buildOrderItems($this->checkoutSession->getQuote());
            }
            $orderRequest->setItems($items);
            if ($dataModel = $this->orderGatewayService->createOrder($orderRequest)->getDataModel()) {
                $this->hokodoQuote->setOrderId($dataModel->getId());
                $this->hokodoQuote->setOfferId('');
                $this->hokodoQuoteRepository->save($this->hokodoQuote);
            } else {
                throw new NotFoundException(__('No order found in API response'));
            }
        } catch (\Exception $e) {
            $data = [
                'message' => 'Hokodo_BNPL: createOrder call failed with error',
                'error' => $e->getMessage(),
            ];
            $this->logger->error(__METHOD__, $data);
            throw new Exception(
                __($e->getMessage())
            );
        }
    }

    /**
     * Patch order request.
     *
     * @return bool
     */
    private function patchOrder(): bool
    {
        //TODO refactor
        try {
            $quote = $this->checkoutSession->getQuote();
            $patchRequest = $this->orderBuilder->buildPatchOrderRequestBase($this->hokodoQuote->getOrderId());
            if (in_array(
                $this->hokodoQuote->getPatchType(),
                [
                    HokodoQuoteInterface::PATCH_ADDRESS,
                    HokodoQuoteInterface::PATCH_ALL,
                ],
                true
            )
            ) {
                $patchRequest->setCustomer($this->orderBuilder->buildPatchCustomerAddress($quote));
            }
            if (in_array(
                $this->hokodoQuote->getPatchType(),
                [
                    HokodoQuoteInterface::PATCH_ITEMS,
                    HokodoQuoteInterface::PATCH_SHIPPING,
                    HokodoQuoteInterface::PATCH_ALL,
                ],
                true
            )
            ) {
                $patchRequest
                    ->setTotalAmount(
                        (int) round($this->cartTotalRepository->get($quote->getId())->getBaseGrandTotal() * 100)
                    )
                    ->setTaxAmount(0);
                if ($this->config->getValue(Config::TOTALS_FIX)) {
                    $items[] = $this->orderBuilder->buildTotalItem($this->checkoutSession->getQuote());
                } else {
                    $items = $this->orderBuilder->buildOrderItems($this->checkoutSession->getQuote());
                }
                $patchRequest->setItems($items);
            }
            $this->orderGatewayService->patchOrder($patchRequest);
            $this->hokodoQuote->setOfferId('');
            return true;
        } catch (\Exception $e) {
            $data = [
                'message' => 'Hokodo_BNPL: createUser call failed with error',
                'error' => $e->getMessage(),
            ];
            $this->logger->error(__METHOD__, $data);
            return false;
        }
    }

    /**
     * Get new offer method.
     *
     * @return PaymentOffersInterface
     *
     * @throws CommandException
     * @throws NoSuchEntityException
     * @throws NotFoundException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    private function getOffer(): PaymentOffersInterface
    {
        try {
            if ($this->hokodoQuote->getOfferId()) {
                $offer = $this->offerGatewayService->getOffer(['id' => $this->hokodoQuote->getOfferId()]);
                if ($this->isPaymentPlanHaveStatus($offer->getDataModel(), PaymentPlanInterface::STATUS_EXPIRED)) {
                    $this->hokodoQuote->setOfferId('');
                }
            }
            if (!$this->hokodoQuote->getOfferId()) {
                $offer = $this->offerGatewayService->createOffer(
                    $this->offerBuilder->build($this->hokodoQuote->getOrderId())
                );
            }
            if (($dataModel = $offer->getDataModel())) {
                $this->hokodoQuote->setOfferId($dataModel->getId());
                $this->hokodoQuote->setPatchType(null);
                $this->hokodoQuoteRepository->save($this->hokodoQuote);

                $dataModel->setIsEligible(
                    $this->isPaymentPlanHaveStatus($dataModel, PaymentPlanInterface::STATUS_OFFERED)
                );

                return $dataModel;
            }
            throw new NotFoundException(__('No offer found in API response'));
        } catch (\Exception $e) {
            $this->hokodoQuote
                ->setOrderId('')
                ->setOfferId('');
            $this->hokodoQuoteRepository->save($this->hokodoQuote);
            throw new Exception(
                new Phrase($e->getMessage())
            );
        }
    }

    /**
     * Check if the provided status exists within the offer's payment plans.
     *
     * @param PaymentOffersInterface $offer
     * @param string                 $status
     *
     * @return bool
     */
    public function isPaymentPlanHaveStatus(PaymentOffersInterface $offer, string $status): bool
    {
        foreach ($offer->getOfferedPaymentPlans() as $offeredPaymentPlan) {
            if ($offeredPaymentPlan->getStatus() === $status) {
                return true;
            }
        }
        return false;
    }
}
