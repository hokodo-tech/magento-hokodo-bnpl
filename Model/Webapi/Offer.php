<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Hokodo\BNPL\Model\Webapi;

use Hokodo\BNPL\Api\Data\HokodoQuoteInterface;
use Hokodo\BNPL\Api\Data\PaymentOffersInterface;
use Hokodo\BNPL\Api\Data\Webapi\OfferRequestInterface;
use Hokodo\BNPL\Api\Data\Webapi\OfferResponseInterface;
use Hokodo\BNPL\Api\Data\Webapi\OfferResponseInterfaceFactory;
use Hokodo\BNPL\Api\HokodoQuoteRepositoryInterface;
use Hokodo\BNPL\Api\Webapi\OfferInterface;
use Hokodo\BNPL\Gateway\Service\Offer as OfferGatewayService;
use Hokodo\BNPL\Gateway\Service\Order as OrderGatewayService;
use Hokodo\BNPL\Gateway\Service\Organisation as OrganisationService;
use Hokodo\BNPL\Gateway\Service\User as UserService;
use Hokodo\BNPL\Model\RequestBuilder\OfferBuilder;
use Hokodo\BNPL\Model\RequestBuilder\OrderBuilder;
use Hokodo\BNPL\Model\RequestBuilder\OrganisationBuilder;
use Hokodo\BNPL\Model\RequestBuilder\UserBuilder;
use Magento\Checkout\Model\Session;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\NotFoundException;
use Magento\Framework\Webapi\Exception;
use Magento\Payment\Gateway\Command\CommandException;
use Magento\Quote\Api\CartRepositoryInterface;
use Psr\Log\LoggerInterface;

class Offer implements OfferInterface
{
    /**
     * @var HokodoQuoteInterface|null
     */
    private ?HokodoQuoteInterface $hokodoQuote;

    /**
     * @var OfferResponseInterfaceFactory
     */
    private OfferResponseInterfaceFactory $responseInterfaceFactory;

    /**
     * @var CartRepositoryInterface
     */
    private CartRepositoryInterface $cartRepository;

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
     * @param OfferResponseInterfaceFactory  $responseInterfaceFactory
     * @param CartRepositoryInterface        $cartRepository
     * @param OrderGatewayService            $orderGatewayService
     * @param OfferGatewayService            $offerGatewayService
     * @param Session                        $checkoutSession
     * @param HokodoQuoteRepositoryInterface $hokodoQuoteRepository
     * @param OrderBuilder                   $orderBuilder
     * @param LoggerInterface                $logger
     * @param OrganisationBuilder            $organisationBuilder
     * @param OrganisationService            $organisationService
     * @param UserBuilder                    $userBuilder
     * @param UserService                    $userService
     * @param OfferBuilder                   $offerBuilder
     */
    public function __construct(
        OfferResponseInterfaceFactory $responseInterfaceFactory,
        CartRepositoryInterface $cartRepository,
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
        OfferBuilder $offerBuilder
    ) {
        $this->responseInterfaceFactory = $responseInterfaceFactory;
        $this->cartRepository = $cartRepository;
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
        if (($companyId = $payload->getCompanyId()) !== $this->hokodoQuote->getCompanyId()) {
            if (!$this->hokodoQuote->getQuoteId()) {
                $this->hokodoQuote->setQuoteId((int) $this->checkoutSession->getQuoteId());
                $this->hokodoQuote->setCompanyId($companyId);
            }
            $this->createOrganisation($companyId);
            $this->createUser();
            $this->createOrder();
        } else {
            //Fallback in case patch failed
            if (!$this->hokodoQuote->getOrderId() ||
                ($this->hokodoQuote->getPatchType() !== null && !$this->patchOrder())
            ) {
                $this->createOrder();
            }
        }

        return $this->responseInterfaceFactory->create()->setOffer($this->getOffer());
    }

    /**
     * Create hokodo organisation request.
     *
     * @param string $companyId
     *
     * @return void
     *
     * @throws Exception
     */
    private function createOrganisation(string $companyId): void
    {
        try {
            $organisation = $this->organisationService->createOrganisation(
                $this->organisationBuilder->build($companyId)
            );
            if ($dataModel = $organisation->getDataModel()) {
                $this->hokodoQuote->setOrganisationId($dataModel->getId());
                $this->hokodoQuoteRepository->save($this->hokodoQuote);
            } else {
                throw new NotFoundException(__('No organisation found in API response'));
            }
        } catch (\Exception $e) {
            $this->logger->error(__('Hokodo_BNPL: createOrganisation call failed with error - %1', $e->getMessage()));
            throw new Exception(
//                __('There was an error during payment method set up. Please reload the page or try again later.')
            //TODO REMOVE BEFORE GOING TO PROD
                __('%1', $e->getMessage())
            );
        }
    }

    /**
     * Create hokodo user request.
     *
     * @return void
     *
     * @throws Exception
     */
    private function createUser(): void
    {
        try {
            $customer = $this->checkoutSession->getQuote()->getCustomer();
            $user = $this->userService->createUser(
                $this->userBuilder->build(
                    $customer->getEmail(),
                    $customer->getFirstname() . ' ' . $customer->getLastname(),
                    $this->hokodoQuote->getOrganisationId()
                )
            );
            if ($dataModel = $user->getDataModel()) {
                $this->hokodoQuote->setUserId($dataModel->getId());
                $this->hokodoQuoteRepository->save($this->hokodoQuote);
            } else {
                throw new NotFoundException(__('No user found in API response'));
            }
        } catch (\Exception $e) {
            $this->logger->error(__('Hokodo_BNPL: createUser call failed with error - %1', $e->getMessage()));
            throw new Exception(
//                __('There was an error during payment method set up. Please reload the page or try again later.')
            //TODO REMOVE BEFORE GOING TO PROD
                __('%1', $e->getMessage())
            );
        }
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
            $orderRequest->setItems($this->orderBuilder->buildOrderItems($this->checkoutSession->getQuote()));
            if ($dataModel = $this->orderGatewayService->createOrder($orderRequest)->getDataModel()) {
                $this->hokodoQuote->setOrderId($dataModel->getId());
                $this->hokodoQuoteRepository->save($this->hokodoQuote);
            } else {
                throw new NotFoundException(__('No order found in API response'));
            }
        } catch (\Exception $e) {
            $this->logger->error(__('Hokodo_BNPL: createOrder call failed with error - %1', $e->getMessage()));
            throw new Exception(
//                __('There was an error during payment method set up. Please reload the page or try again later.')
            //TODO REMOVE BEFORE GOING TO PROD
                __('%1', $e->getMessage())
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
                    ->setTotalAmount((int) ($quote->getGrandTotal() * 100))
                    ->setTaxAmount((int) ($quote->getShippingAddress()->getTaxAmount() * 100));
                $patchRequest->setItems($this->orderBuilder->buildOrderItems($quote));
            }
            $this->orderGatewayService->patchOrder($patchRequest);
            return true;
        } catch (\Exception $e) {
            $this->logger->error(__('Hokodo_BNPL: createUser call failed with error - %1', $e->getMessage()));
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
        $offer = $this->offerGatewayService->createOffer($this->offerBuilder->build($this->hokodoQuote->getOrderId()));
        if ($dataModel = $offer->getDataModel()) {
            $quote = $this->checkoutSession->getQuote();
            $quote->setData('payment_offer_id', $dataModel->getId());
            $this->cartRepository->save($quote);

            $this->hokodoQuote->setOfferId($dataModel->getId());
            $this->hokodoQuote->setPatchType(null);
            $this->hokodoQuoteRepository->save($this->hokodoQuote);

            return $dataModel;
        }
        //TODO Handle errors by reseting the whole quote
        throw new NotFoundException(__('No offer found in API response'));
    }
}
