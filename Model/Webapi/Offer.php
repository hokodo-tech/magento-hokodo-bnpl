<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Hokodo\BNPL\Model\Webapi;

use Hokodo\BNPL\Api\Data\Gateway\CreateOfferRequestInterface;
use Hokodo\BNPL\Api\Data\Gateway\CreateOfferRequestInterfaceFactory;
use Hokodo\BNPL\Api\Data\Gateway\OfferUrlsInterface;
use Hokodo\BNPL\Api\Data\Gateway\OfferUrlsInterfaceFactory;
use Hokodo\BNPL\Api\Data\HokodoQuoteInterface;
use Hokodo\BNPL\Api\Data\Webapi\OfferRequestInterface;
use Hokodo\BNPL\Api\Data\Webapi\OfferResponseInterface;
use Hokodo\BNPL\Api\Data\Webapi\OfferResponseInterfaceFactory;
use Hokodo\BNPL\Api\HokodoQuoteRepositoryInterface;
use Hokodo\BNPL\Api\Webapi\OfferInterface;
use Hokodo\BNPL\Gateway\Service\Offer as OfferGatewayService;
use Hokodo\BNPL\Gateway\Service\Order as OrderGatewayService;
use Hokodo\BNPL\Model\RequestBuilder\OrderBuilder;
use Magento\Checkout\Model\Session;
use Magento\Framework\Webapi\Exception;
use Magento\Payment\Gateway\Command\ResultInterface;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Api\Data\CartInterface;
use Psr\Log\LoggerInterface;

class Offer implements OfferInterface
{
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
     * @var CreateOfferRequestInterfaceFactory
     */
    private CreateOfferRequestInterfaceFactory $createOfferRequestFactory;

    /**
     * @var OfferGatewayService
     */
    private OfferGatewayService $offerGatewayService;

    /**
     * @var OfferUrlsInterfaceFactory
     */
    private OfferUrlsInterfaceFactory $offerUrlsFactory;

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
     * @param OfferResponseInterfaceFactory      $responseInterfaceFactory
     * @param CartRepositoryInterface            $cartRepository
     * @param OrderGatewayService                $orderGatewayService
     * @param CreateOfferRequestInterfaceFactory $createOfferRequestFactory
     * @param OfferGatewayService                $offerGatewayService
     * @param OfferUrlsInterfaceFactory          $offerUrlsFactory
     * @param Session                            $checkoutSession
     * @param HokodoQuoteRepositoryInterface     $hokodoQuoteRepository
     * @param OrderBuilder                       $orderBuilder
     * @param LoggerInterface                    $logger
     */
    public function __construct(
        OfferResponseInterfaceFactory $responseInterfaceFactory,
        CartRepositoryInterface $cartRepository,
        OrderGatewayService $orderGatewayService,
        CreateOfferRequestInterfaceFactory $createOfferRequestFactory,
        OfferGatewayService $offerGatewayService,
        OfferUrlsInterfaceFactory $offerUrlsFactory,
        Session $checkoutSession,
        HokodoQuoteRepositoryInterface $hokodoQuoteRepository,
        OrderBuilder $orderBuilder,
        LoggerInterface $logger
    ) {
        $this->responseInterfaceFactory = $responseInterfaceFactory;
        $this->cartRepository = $cartRepository;
        $this->orderGatewayService = $orderGatewayService;
        $this->createOfferRequestFactory = $createOfferRequestFactory;
        $this->offerGatewayService = $offerGatewayService;
        $this->offerUrlsFactory = $offerUrlsFactory;
        $this->checkoutSession = $checkoutSession;
        $this->hokodoQuoteRepository = $hokodoQuoteRepository;
        $this->orderBuilder = $orderBuilder;
        $this->logger = $logger;
    }

    /**
     * Request offer webapi handler.
     *
     * @param OfferRequestInterface $payload
     *
     * @return OfferResponseInterface
     *
     * @throws Exception
     */
    public function requestNew(OfferRequestInterface $payload): OfferResponseInterface
    {
        $response = $this->responseInterfaceFactory->create()->setId('');
        /* @var $response OfferResponseInterface */

        try {
            $quote = $this->checkoutSession->getQuote();
            $hokodoQuote = $this->hokodoQuoteRepository->getByQuoteId($quote->getId());
            $patchFailed = true;
            if ($hokodoQuote->getOrderId() && $hokodoQuote->getPatchRequired() !== null) {
                try {
                    if (($orderResponse = $this->patchOrder($quote, $hokodoQuote))
                        && $orderResponse->getDataModel()) {
                        $patchFailed = false;
                    }
                } catch (\Exception $e) {
                    $this->logger->warning(
                        __(
                            'Hokodo_BNPL: patching order %1 failed with error - %1. Falling back to create order.',
                            $hokodoQuote->getOrderId(),
                            $e->getMessage()
                        )
                    );
                }
            }
            if ($patchFailed && $orderResponse = $this->createOrder($quote, $payload)) {
                $hokodoQuote->setOrderId($orderResponse->getDataModel()->getId());
            }
            //Set Offer
            $urls = $this->offerUrlsFactory->create();
            /* @var $urls OfferUrlsInterface */
            //TODO add correct urls
            $urls->setSuccessUrl('http://hokodo.local')
                ->setCancelUrl('http://hokodo.local')
                ->setFailureUrl('http://hokodo.local')
                ->setMerchantTermsUrl('http://hokodo.local')
                ->setNotificationUrl('http://hokodo.local');
            $createOfferRequest = $this->createOfferRequestFactory->create();
            /* @var $createOfferRequest CreateOfferRequestInterface */
            //TODO locales
            $createOfferRequest
                ->setOrder($hokodoQuote->getOrderId())
                ->setUrls($urls)
                ->setLocale('en-gb')
                ->setMetadata([]);

            $offer = $this->offerGatewayService->createOffer($createOfferRequest);
            if ($dataModel = $offer->getDataModel()) {
                $response->setOffer($dataModel);
                $quote->setData('payment_offer_id', $dataModel->getId());
                $this->cartRepository->save($quote);
                $hokodoQuote->setOfferId($dataModel->getId());
                $hokodoQuote->setPatchRequired(null);
            } else {
                $response->setId('');
            }
            $this->hokodoQuoteRepository->save($hokodoQuote);
        } catch (\Exception $e) {
            $this->logger->error(__('Hokodo_BNPL: createOffer call failed with error - %1', $e->getMessage()));
            throw new Exception(
                __('There was an error during payment method set up. Please reload the page or try again later.')
            );
        }
        return $response;
    }

    /**
     * Create Order request.
     *
     * @param CartInterface         $quote
     * @param OfferRequestInterface $payload
     *
     * @return ResultInterface|null
     *
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\NotFoundException
     * @throws \Magento\Payment\Gateway\Command\CommandException
     */
    private function createOrder(CartInterface $quote, OfferRequestInterface $payload): ?ResultInterface
    {
        $orderRequest = $this->orderBuilder->buildOrderRequestBase($quote);
        $orderRequest->setCustomer(
            $this->orderBuilder->buildCustomer($quote)
                ->setUser($payload->getUserId())
                ->setOrganisation($payload->getOrganisationId())
        );
        $orderRequest->setItems($this->orderBuilder->buildOrderItems($quote));
        return $this->orderGatewayService->createOrder($orderRequest);
    }

    /**
     * Patch order request.
     *
     * @param CartInterface        $quote
     * @param HokodoQuoteInterface $hokodoQuote
     *
     * @return ResultInterface|null
     *
     * @throws \Magento\Framework\Exception\NotFoundException
     * @throws \Magento\Payment\Gateway\Command\CommandException
     */
    private function patchOrder(CartInterface $quote, HokodoQuoteInterface $hokodoQuote): ?ResultInterface
    {
        $patchRequest = $this->orderBuilder->buildPatchOrderRequestBase($hokodoQuote->getOrderId());
        if ($hokodoQuote->getPatchRequired() === HokodoQuoteInterface::PATCH_ADDRESS) {
            $patchRequest->setCustomer($this->orderBuilder->buildPatchCustomerAddress($quote));
        }
        if ($hokodoQuote->getPatchRequired() === HokodoQuoteInterface::PATCH_ITEMS) {
            $patchRequest
                ->setTotalAmount((int) ($quote->getGrandTotal() * 100))
                ->setTaxAmount((int) ($quote->getShippingAddress()->getTaxAmount() * 100));
            $patchRequest->setItems($this->orderBuilder->buildOrderItems($quote));
        }
        if ($hokodoQuote->getPatchRequired() === HokodoQuoteInterface::PATCH_BOTH) {
            $patchRequest->setCustomer($this->orderBuilder->buildPatchCustomerAddress($quote));
            $patchRequest
                ->setTotalAmount((int) ($quote->getGrandTotal() * 100))
                ->setTaxAmount((int) ($quote->getShippingAddress()->getTaxAmount() * 100));
            $patchRequest->setItems($this->orderBuilder->buildOrderItems($quote));
        }

        return $this->orderGatewayService->patchOrder($patchRequest);
    }
}
