<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Hokodo\BNPL\Model\Webapi;

use Hokodo\BNPL\Api\Data\Gateway\CreateOfferRequestInterface;
use Hokodo\BNPL\Api\Data\Gateway\CreateOfferRequestInterfaceFactory;
use Hokodo\BNPL\Api\Data\Gateway\CreateOrderRequestInterface as GatewayRequest;
use Hokodo\BNPL\Api\Data\Gateway\CreateOrderRequestInterfaceFactory as GatewayRequestFactory;
use Hokodo\BNPL\Api\Data\Gateway\CustomerAddressInterface;
use Hokodo\BNPL\Api\Data\Gateway\CustomerAddressInterfaceFactory;
use Hokodo\BNPL\Api\Data\Gateway\OfferUrlsInterface;
use Hokodo\BNPL\Api\Data\Gateway\OfferUrlsInterfaceFactory;
use Hokodo\BNPL\Api\Data\Gateway\OrderCustomerInterface;
use Hokodo\BNPL\Api\Data\Gateway\OrderCustomerInterfaceFactory;
use Hokodo\BNPL\Api\Data\Gateway\OrderItemInterface;
use Hokodo\BNPL\Api\Data\Gateway\OrderItemInterfaceFactory;
use Hokodo\BNPL\Api\Data\Webapi\CreateOrderRequestInterface;
use Hokodo\BNPL\Api\Data\Webapi\CreateOrderResponseInterface;
use Hokodo\BNPL\Api\Data\Webapi\CreateOrderResponseInterfaceFactory;
use Hokodo\BNPL\Api\Webapi\OrderInterface;
use Hokodo\BNPL\Gateway\Service\Offer as OfferGatewayService;
use Hokodo\BNPL\Gateway\Service\Order as OrderGatewayService;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\ProductMetadataInterface;
use Magento\Framework\Component\ComponentRegistrar;
use Magento\Framework\Component\ComponentRegistrarInterface;
use Magento\Framework\Filesystem\Directory\ReadFactory;
use Magento\Framework\Phrase;
use Magento\Framework\Stdlib\DateTime\DateTimeFactory;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Api\Data\AddressInterface;
use Magento\Quote\Api\Data\CartInterface;
use Magento\Quote\Model\Quote\Item;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Tax\Model\Config as TaxConfig;

class Order implements OrderInterface
{
    /**
     * @var CreateOrderResponseInterfaceFactory
     */
    private CreateOrderResponseInterfaceFactory $responseInterfaceFactory;

    /**
     * @var CartRepositoryInterface
     */
    private CartRepositoryInterface $cartRepository;

    /**
     * @var GatewayRequestFactory
     */
    private GatewayRequestFactory $gatewayRequestFactory;

    /**
     * @var OrderCustomerInterfaceFactory
     */
    private OrderCustomerInterfaceFactory $orderCustomerFactory;

    /**
     * @var CustomerAddressInterfaceFactory
     */
    private CustomerAddressInterfaceFactory $customerAddressFactory;

    /**
     * @var OrderItemInterfaceFactory
     */
    private OrderItemInterfaceFactory $orderItemFactory;

    /**
     * @var ScopeConfigInterface
     */
    private ScopeConfigInterface $config;

    /**
     * @var ProductMetadataInterface
     */
    private ProductMetadataInterface $productMetadata;

    /**
     * @var StoreManagerInterface
     */
    private StoreManagerInterface $storeManager;

    /**
     * @var DateTimeFactory
     */
    private DateTimeFactory $dateTimeFactory;

    /**
     * @var ComponentRegistrarInterface
     */
    private ComponentRegistrarInterface $componentRegistrar;

    /**
     * @var ReadFactory
     */
    private ReadFactory $readFactory;

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
     * @param CreateOrderResponseInterfaceFactory $responseInterfaceFactory
     * @param CartRepositoryInterface             $cartRepository
     * @param GatewayRequestFactory               $gatewayRequestFactory
     * @param OrderCustomerInterfaceFactory       $orderCustomerFactory
     * @param CustomerAddressInterfaceFactory     $customerAddressFactory
     * @param OrderItemInterfaceFactory           $orderItemFactory
     * @param ScopeConfigInterface                $config
     * @param ProductMetadataInterface            $productMetadata
     * @param StoreManagerInterface               $storeManager
     * @param DateTimeFactory                     $dateTimeFactory
     * @param ComponentRegistrarInterface         $componentRegistrar
     * @param ReadFactory                         $readFactory
     * @param OrderGatewayService                 $orderGatewayService
     * @param CreateOfferRequestInterfaceFactory  $createOfferRequestFactory
     * @param OfferGatewayService                 $offerGatewayService
     * @param OfferUrlsInterfaceFactory           $offerUrlsFactory
     */
    public function __construct(
        CreateOrderResponseInterfaceFactory $responseInterfaceFactory,
        CartRepositoryInterface $cartRepository,
        GatewayRequestFactory $gatewayRequestFactory,
        OrderCustomerInterfaceFactory $orderCustomerFactory,
        CustomerAddressInterfaceFactory $customerAddressFactory,
        OrderItemInterfaceFactory $orderItemFactory,
        ScopeConfigInterface $config,
        ProductMetadataInterface $productMetadata,
        StoreManagerInterface $storeManager,
        DateTimeFactory $dateTimeFactory,
        ComponentRegistrarInterface $componentRegistrar,
        ReadFactory $readFactory,
        OrderGatewayService $orderGatewayService,
        CreateOfferRequestInterfaceFactory $createOfferRequestFactory,
        OfferGatewayService $offerGatewayService,
        OfferUrlsInterfaceFactory $offerUrlsFactory
    ) {
        $this->responseInterfaceFactory = $responseInterfaceFactory;
        $this->cartRepository = $cartRepository;
        $this->gatewayRequestFactory = $gatewayRequestFactory;
        $this->orderCustomerFactory = $orderCustomerFactory;
        $this->customerAddressFactory = $customerAddressFactory;
        $this->orderItemFactory = $orderItemFactory;
        $this->config = $config;
        $this->productMetadata = $productMetadata;
        $this->storeManager = $storeManager;
        $this->dateTimeFactory = $dateTimeFactory;
        $this->componentRegistrar = $componentRegistrar;
        $this->readFactory = $readFactory;
        $this->orderGatewayService = $orderGatewayService;
        $this->createOfferRequestFactory = $createOfferRequestFactory;
        $this->offerGatewayService = $offerGatewayService;
        $this->offerUrlsFactory = $offerUrlsFactory;
    }

    /**
     * Create order request webapi handler.
     *
     * @param CreateOrderRequestInterface $payload
     *
     * @return CreateOrderResponseInterface
     */
    public function create(CreateOrderRequestInterface $payload): CreateOrderResponseInterface
    {
        $response = $this->responseInterfaceFactory->create();
        /* @var $response CreateOrderResponseInterface */

        try {
            $quote = $this->cartRepository->get($payload->getQuoteId());
            $createOrderRequest = $this->buildOrderRequestBase($quote);
            $createOrderRequest->setCustomer(
                $this->buildCustomer($quote)
                    ->setUser($payload->getUserId())
                    ->setOrganisation($payload->getOrganisationId())
            );
            $createOrderRequest->setItems($this->buildOrderItems($quote));
            $order = $this->orderGatewayService->createOrder($createOrderRequest);
            if ($dataModel = $order->getDataModel()) {
                $response->setId($dataModel->getId());
            }
            //Set Offer
            $urls = $this->offerUrlsFactory->create();
            /* @var $urls OfferUrlsInterface */
            $urls->setSuccessUrl('http://hokodo.local')
                ->setCancelUrl('http://hokodo.local')
                ->setFailureUrl('http://hokodo.local')
                ->setMerchantTermsUrl('http://hokodo.local')
                ->setNotificationUrl('http://hokodo.local');
            $createOfferRequest = $this->createOfferRequestFactory->create();
            /* @var $createOfferRequest CreateOfferRequestInterface */
            $createOfferRequest
                ->setOrder($response->getId())
                ->setUrls($urls)
                ->setLocale('en-gb')
                ->setMetadata([]);

            $offer = $this->offerGatewayService->createOffer($createOfferRequest);
            if ($dataModel = $offer->getDataModel()) {
                $response->setOffer($dataModel);
                $quote->setData('payment_offer_id', $dataModel->getId());
                $this->cartRepository->save($quote);
            }
        } catch (\Exception $e) {
            //TODO Handle Exception
            $response->setId('');
        }
        return $response;
    }

//    public function patch(CreateOrderRequestInterface $payload): CreateOrderResponseInterface
//    {
//        $response = $this->responseInterfaceFactory->create();
//        /* @var $response CreateOrderResponseInterface */
//
//        try {
//            $quote = $this->cartRepository->get($payload->getQuoteId());
//            $createOrderRequest = $this->buildOrderRequestBase($quote);
//            $createOrderRequest->setCustomer(
//                $this->buildCustomer($quote)
//                    ->setUser($payload->getUserId())
//                    ->setOrganisation($payload->getOrganisationId())
//            );
//            $createOrderRequest->setItems($this->buildOrderItems($quote));
//            $order = $this->orderGatewayService->createOrder($createOrderRequest);
//            if ($dataModel = $order->getDataModel()) {
//                $response->setId($dataModel->getId());
//            }
//        } catch (\Exception $e) {
//            $response->setId('');
//        }
//
//        return $response;
//    }

    /**
     * Build Order create request object with base info from quote.
     *
     * @param CartInterface $quote
     *
     * @return GatewayRequest
     *
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    private function buildOrderRequestBase(CartInterface $quote): GatewayRequest
    {
        $orderRequest = $this->gatewayRequestFactory->create();
        return $orderRequest
            ->setUniqueId(
                'magento-temp-' . hash('md5', $this->storeManager->getStore()->getCode() .
                    $this->storeManager->getStore()->getName() . $quote->getId())
            )
            ->setStatus('draft')
            ->setCurrency($quote->getQuoteCurrencyCode())
            ->setTotalAmount((int) ($quote->getGrandTotal() * 100))
            ->setTaxAmount((int) ($quote->getShippingAddress()->getTaxAmount() * 100))
            ->setOrderDate($this->dateTimeFactory->create()->gmtDate('Y-m-d', time()))
            ->setMetadata(
                [
                    'Magento Version: ' . $this->productMetadata->getVersion(),
                    'Hokodo Module Version: ' . $this->getModuleVersion(),
                    'PHP version: ' . PHP_VERSION,
                ]
            );
    }

    /**
     * Build Customer object method.
     *
     * @param CartInterface $quote
     *
     * @return OrderCustomerInterface
     */
    private function buildCustomer(CartInterface $quote): OrderCustomerInterface
    {
        $customer = $quote->getCustomer();
        $customerRequest = $this->orderCustomerFactory->create();
        return $customerRequest
            ->setType($customer->getId() ? 'registered' : 'guest')
            ->setDeliveryAddress($this->buildCustomerAddress($quote->getShippingAddress()))
            ->setInvoiceAddress($this->buildCustomerAddress($quote->getBillingAddress()));
    }

    /**
     * Build customer address method.
     *
     * @param AddressInterface $address
     *
     * @return CustomerAddressInterface
     */
    private function buildCustomerAddress(AddressInterface $address): CustomerAddressInterface
    {
        return $this->customerAddressFactory->create()
            ->setName($address->getName())
            ->setAddressLineOne($address->getStreetLine(1))
            ->setAddressLineTwo($address->getStreetLine(2))
            ->setCity($address->getCity())
            ->setCountry($address->getCountry())
            ->setPostcode($address->getPostcode() ?? '');
    }

    /**
     * Build Order items method.
     *
     * @param CartInterface $quote
     *
     * @return array
     */
    private function buildOrderItems(CartInterface $quote): array
    {
        $items = [];
        foreach ($quote->getAllVisibleItems() as $item) {
            $items[] = $this->buildOrderItem($item);
        }
        $items[] = $this->buildOrderShipping($quote);

        return $items;
    }

    /**
     * Build Order product item.
     *
     * @param Item $item
     *
     * @return OrderItemInterface
     */
    private function buildOrderItem(Item $item): OrderItemInterface
    {
        //Item total calculation adjustment based on tax settings in Magento
        if ($this->isApplyTaxAdjustment($item->getStoreId())) {
            $totalAmount = $item->getRowTotalInclTax() - $item->getDiscountAmount() *
                (($item->getTaxPercent() + 100) / 100);
        } else {
            $totalAmount = $item->getRowTotalInclTax() - $item->getDiscountAmount();
        }

        return $this->orderItemFactory->create()
            ->setItemId($item->getId())
            ->setType('product')
            ->setDescription($item->getName())
            ->setReference($item->getSku())
            ->setQuantity((string) $item->getQty())
            ->setUnitPrice((int) round($totalAmount / $item->getQty() * 100))
            ->setTaxRate(number_format((float) $item->getTaxPercent(), 2))
            ->setTaxAmount((int) ($item->getTaxAmount() * 100))
            ->setTotalAmount((int) ($totalAmount * 100));
    }

    /**
     * Build Order shipping item.
     *
     * @param CartInterface $quote
     *
     * @return OrderItemInterface
     */
    private function buildOrderShipping(CartInterface $quote): OrderItemInterface
    {
        $shippingAddress = $quote->getShippingAddress();

        return $this->orderItemFactory->create()
            ->setItemId($quote->getId() . '-shipping')
            ->setType('shipping')
            ->setDescription($shippingAddress->getShippingDescription() ?? '')
            ->setReference($shippingAddress->getShippingMethod())
            ->setQuantity('1')
            ->setUnitPrice((int) ($shippingAddress->getShippingInclTax() * 100))
            ->setTaxRate('0')
            ->setTaxAmount(0)
            ->setTotalAmount((int) ($shippingAddress->getShippingInclTax() * 100));
    }

    /**
     * Whether tax adjustment is necessary.
     *
     * @param int $storeId
     *
     * @return bool
     */
    private function isApplyTaxAdjustment(int $storeId = 0): bool
    {
        return $this->config->getValue(
            TaxConfig::CONFIG_XML_PATH_APPLY_AFTER_DISCOUNT,
            ScopeInterface::SCOPE_STORE,
            $storeId
        ) &&
            !$this->config->getValue(
                TaxConfig::CONFIG_XML_PATH_PRICE_INCLUDES_TAX,
                ScopeInterface::SCOPE_STORE,
                $storeId
            );
    }

    /**
     * Get module composer version.
     *
     * @return Phrase|string|void
     */
    private function getModuleVersion()
    {
        try {
            $path = $this->componentRegistrar->getPath(
                ComponentRegistrar::MODULE,
                'Hokodo_BNPL'
            );
            $directoryRead = $this->readFactory->create($path);
            $composerJsonData = $directoryRead->readFile('composer.json');
            return json_decode($composerJsonData, false, 512, JSON_THROW_ON_ERROR)->version;
        } catch (\Exception $e) {
            return __('Read Error!');
        }
    }
}
