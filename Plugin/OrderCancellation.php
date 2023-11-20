<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Hokodo\BNPL\Plugin;

use Hokodo\BNPL\Api\Data\Gateway\DeferredPaymentsPostSaleActionInterfaceFactory;
use Hokodo\BNPL\Api\HokodoQuoteRepositoryInterface;
use Hokodo\BNPL\Gateway\Config\Config;
use Hokodo\BNPL\Gateway\Service\Offer;
use Hokodo\BNPL\Gateway\Service\Order;
use Hokodo\BNPL\Gateway\Service\PostSale;
use Magento\Quote\Api\CartManagementInterface;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Api\Data\PaymentInterface;
use Magento\Sales\Model\OrderFactory;

class OrderCancellation
{
    /**
     * @var HokodoQuoteRepositoryInterface
     */
    private HokodoQuoteRepositoryInterface $hokodoQuoteRepository;

    /**
     * @var CartRepositoryInterface
     */
    private CartRepositoryInterface $cartRepository;

    /**
     * @var OrderFactory
     */
    private OrderFactory $orderFactory;

    /**
     * @var Offer
     */
    private Offer $offerService;

    /**
     * @var Order
     */
    private Order $orderService;

    /**
     * @var PostSale
     */
    private PostSale $postSaleService;

    /**
     * @var DeferredPaymentsPostSaleActionInterfaceFactory
     */
    private DeferredPaymentsPostSaleActionInterfaceFactory $postSaleActionInterfaceFactory;

    /**
     * @param HokodoQuoteRepositoryInterface                 $hokodoQuoteRepository
     * @param CartRepositoryInterface                        $cartRepository
     * @param OrderFactory                                   $orderFactory
     * @param Offer                                          $offerService
     * @param Order                                          $orderService
     * @param PostSale                                       $postSaleService
     * @param DeferredPaymentsPostSaleActionInterfaceFactory $postSaleActionInterfaceFactory
     */
    public function __construct(
        HokodoQuoteRepositoryInterface $hokodoQuoteRepository,
        CartRepositoryInterface $cartRepository,
        OrderFactory $orderFactory,
        Offer $offerService,
        Order $orderService,
        PostSale $postSaleService,
        DeferredPaymentsPostSaleActionInterfaceFactory $postSaleActionInterfaceFactory
    ) {
        $this->hokodoQuoteRepository = $hokodoQuoteRepository;
        $this->cartRepository = $cartRepository;
        $this->orderFactory = $orderFactory;
        $this->offerService = $offerService;
        $this->orderService = $orderService;
        $this->postSaleService = $postSaleService;
        $this->postSaleActionInterfaceFactory = $postSaleActionInterfaceFactory;
    }

    /**
     * Cancel the order if backend fails.
     *
     * @param CartManagementInterface $subject
     * @param callable                $proceed
     * @param int                     $cartId
     * @param PaymentInterface|null   $paymentMethod
     *
     * @return int
     *
     * @throws \Exception
     */
    public function aroundPlaceOrder(
        CartManagementInterface $subject,
        callable $proceed,
        $cartId,
        PaymentInterface $paymentMethod = null
    ) {
        try {
            return $proceed($cartId, $paymentMethod);
        } catch (\Exception $e) {
            if (!$paymentMethod || $paymentMethod->getMethod() === Config::CODE) {
                $cart = $this->cartRepository->get((int) $cartId);
                $order = $this->orderFactory->create()->loadByIncrementId($cart->getReservedOrderId());
                if (!$order->getId()) {
                    $hokodoQuote = $this->hokodoQuoteRepository->getByQuoteId((int) $cartId);
                    $hokodoOrder = $this->orderService->getOrder(['id' => $hokodoQuote->getOrderId()])->getDataModel();
                    if ($deferredPaymentId = $hokodoOrder->getDeferredPayment()) {
                        $this->postSaleService->voidRemaining(
                            $this->postSaleActionInterfaceFactory->create()
                                ->setPaymentId($deferredPaymentId)
                        );
                    }
                    $hokodoQuote
                        ->setOrderId('')
                        ->setOfferId('');
                    $this->hokodoQuoteRepository->save($hokodoQuote);
                }
            }

            throw $e;
        }
    }
}
