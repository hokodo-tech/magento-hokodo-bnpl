<?php
/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Hokodo\BNPL\Model\RequestBuilder;

use Hokodo\BNPL\Api\Data\Gateway\CreateOrderRequestInterface;
use Hokodo\BNPL\Api\Data\Gateway\CreateOrderRequestInterfaceFactory;
use Hokodo\BNPL\Api\Data\Gateway\CustomerAddressInterface;
use Hokodo\BNPL\Api\Data\Gateway\CustomerAddressInterfaceFactory;
use Hokodo\BNPL\Api\Data\Gateway\OrderCustomerInterface;
use Hokodo\BNPL\Api\Data\Gateway\OrderCustomerInterfaceFactory;
use Hokodo\BNPL\Api\Data\Gateway\OrderItemInterface;
use Hokodo\BNPL\Api\Data\Gateway\OrderItemInterfaceFactory;
use Hokodo\BNPL\Api\Data\Gateway\PatchOrderRequestInterface;
use Hokodo\BNPL\Api\Data\Gateway\PatchOrderRequestInterfaceFactory;
use Hokodo\BNPL\Gateway\Config\Config;
use Magento\Customer\Api\GroupRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\ProductMetadataInterface;
use Magento\Framework\Component\ComponentRegistrar;
use Magento\Framework\Component\ComponentRegistrarInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Filesystem\Directory\ReadFactory;
use Magento\Framework\Phrase;
use Magento\Framework\Stdlib\DateTime\DateTimeFactory;
use Magento\Quote\Api\Data\AddressInterface;
use Magento\Quote\Api\Data\CartInterface;
use Magento\Quote\Model\Quote\Item;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Tax\Model\Config as TaxConfig;

class OrderBuilder
{
    /**
     * @var CreateOrderRequestInterfaceFactory
     */
    private CreateOrderRequestInterfaceFactory $createOrderRequestInterfaceFactory;

    /**
     * @var PatchOrderRequestInterfaceFactory
     */
    private PatchOrderRequestInterfaceFactory $patchOrderRequestInterfaceFactory;

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
     * @var OrderRepositoryInterface
     */
    private OrderRepositoryInterface $orderRepository;

    /**
     * @var SearchCriteriaBuilder
     */
    private SearchCriteriaBuilder $searchCriteriaBuilder;

    /**
     * @var GroupRepositoryInterface
     */
    private GroupRepositoryInterface $groupRepository;

    /**
     * @var Config
     */
    private Config $gatewayConfig;

    /**
     * @param CreateOrderRequestInterfaceFactory $createOrderRequestInterfaceFactory
     * @param PatchOrderRequestInterfaceFactory  $patchOrderRequestInterfaceFactory
     * @param OrderCustomerInterfaceFactory      $orderCustomerFactory
     * @param CustomerAddressInterfaceFactory    $customerAddressFactory
     * @param OrderItemInterfaceFactory          $orderItemFactory
     * @param ScopeConfigInterface               $config
     * @param ProductMetadataInterface           $productMetadata
     * @param StoreManagerInterface              $storeManager
     * @param DateTimeFactory                    $dateTimeFactory
     * @param ComponentRegistrarInterface        $componentRegistrar
     * @param ReadFactory                        $readFactory
     * @param OrderRepositoryInterface           $orderRepository
     * @param SearchCriteriaBuilder              $searchCriteriaBuilder
     * @param GroupRepositoryInterface           $groupRepository
     * @param Config                             $gatewayConfig
     */
    public function __construct(
        CreateOrderRequestInterfaceFactory $createOrderRequestInterfaceFactory,
        PatchOrderRequestInterfaceFactory $patchOrderRequestInterfaceFactory,
        OrderCustomerInterfaceFactory $orderCustomerFactory,
        CustomerAddressInterfaceFactory $customerAddressFactory,
        OrderItemInterfaceFactory $orderItemFactory,
        ScopeConfigInterface $config,
        ProductMetadataInterface $productMetadata,
        StoreManagerInterface $storeManager,
        DateTimeFactory $dateTimeFactory,
        ComponentRegistrarInterface $componentRegistrar,
        ReadFactory $readFactory,
        OrderRepositoryInterface $orderRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        GroupRepositoryInterface $groupRepository,
        Config $gatewayConfig
    ) {
        $this->createOrderRequestInterfaceFactory = $createOrderRequestInterfaceFactory;
        $this->patchOrderRequestInterfaceFactory = $patchOrderRequestInterfaceFactory;
        $this->orderCustomerFactory = $orderCustomerFactory;
        $this->customerAddressFactory = $customerAddressFactory;
        $this->orderItemFactory = $orderItemFactory;
        $this->config = $config;
        $this->productMetadata = $productMetadata;
        $this->storeManager = $storeManager;
        $this->dateTimeFactory = $dateTimeFactory;
        $this->componentRegistrar = $componentRegistrar;
        $this->readFactory = $readFactory;
        $this->orderRepository = $orderRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->groupRepository = $groupRepository;
        $this->gatewayConfig = $gatewayConfig;
    }

    /**
     * Build Order create request object with base info from quote.
     *
     * @param CartInterface $quote
     *
     * @return CreateOrderRequestInterface
     *
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws LocalizedException
     */
    public function buildOrderRequestBase(CartInterface $quote): CreateOrderRequestInterface
    {
        $orderRequest = $this->createOrderRequestInterfaceFactory->create();
        return $orderRequest
            ->setUniqueId(
                'magento-temp-' . hash('md5', $this->storeManager->getStore()->getCode() .
                    $this->storeManager->getStore()->getName() . $quote->getId())
            )
            ->setStatus('draft')
            ->setCurrency($quote->getQuoteCurrencyCode())
            ->setTotalAmount((int) ($quote->getGrandTotal() * 100))
            ->setTaxAmount(0)
            ->setOrderDate($this->dateTimeFactory->create()->gmtDate('Y-m-d', time()))
            ->setMetadata(
                [
                    'Magento Version: ' . $this->productMetadata->getVersion(),
                    'Hokodo Module Version: ' . $this->getModuleVersion(),
                    'PHP version: ' . PHP_VERSION,
                    'Customer' => $this->getCustomerInformation($quote),
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
    public function buildCustomer(CartInterface $quote): OrderCustomerInterface
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
            ->setName($address->getName() ?? '')
            ->setAddressLineOne($address->getStreetLine(1) ?? '')
            ->setAddressLineTwo($address->getStreetLine(2) ?? '')
            ->setCity($address->getCity() ?? '')
            ->setCountry($address->getCountry() ?? '')
            ->setPostcode($address->getPostcode() ?? '');
    }

    /**
     * Build Order items method.
     *
     * @param CartInterface $quote
     *
     * @return array
     */
    public function buildOrderItems(CartInterface $quote): array
    {
        $items = [];
        foreach ($quote->getAllVisibleItems() as $item) {
            $items[] = $this->buildOrderItem($item);
        }
        $items[] = $this->buildOrderShipping($quote);

        return $items;
    }

    /**
     * Builds one total item instead of real products.
     *
     * @param CartInterface $quote
     *
     * @return array
     */
    public function buildTotalItem(CartInterface $quote): OrderItemInterface
    {
        return $this->orderItemFactory->create()
            ->setItemId('totals')
            ->setType('product')
            ->setDescription('Combined totals item')
            ->setQuantity('1')
            ->setUnitPrice((int) round($quote->getGrandTotal() * 100))
            ->setTaxRate('0')
            ->setTaxAmount(0)
            ->setTotalAmount((int) round($quote->getGrandTotal() * 100));
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
            ->setTaxRate('0')
            ->setTaxAmount(0)
            ->setTotalAmount((int) round($totalAmount * 100));
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
        $shipping = $quote->getShippingAddress();
        $shippingTotal = $shipping->getShippingInclTax() - $shipping->getShippingDiscountAmount();

        return $this->orderItemFactory->create()
            ->setItemId($quote->getId() . '-shipping')
            ->setType('shipping')
            ->setDescription($shipping->getShippingDescription() ?? '')
            ->setReference($shipping->getShippingMethod() ?? '')
            ->setQuantity('1')
            ->setUnitPrice((int) ($shippingTotal * 100))
            ->setTaxRate('0')
            ->setTaxAmount(0)
            ->setTotalAmount((int) ($shippingTotal * 100));
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

    /**
     * Get customer metadata information.
     *
     * @param CartInterface $quote
     *
     * @return array|null
     *
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    private function getCustomerInformation(CartInterface $quote): ?array
    {
        if ($this->gatewayConfig->getValue(Config::SEND_PURCHASE_HISTORY) && ($customer = $quote->getCustomer())) {
            $orders = $this->getCustomersOrders((int) $customer->getId());
            return [
                'group_id' => $this->groupRepository->getById($customer->getGroupId())->getCode(),
                'orders_qty' => count($orders) ?: null,
                'total_amount' => count($orders) ? $this->getOrdersTotalAmount($orders) : null,
            ];
        }
        return null;
    }

    /**
     * Get customer's orders.
     *
     * @param int $customerId
     *
     * @return OrderInterface[]
     */
    private function getCustomersOrders(int $customerId): array
    {
        return $this->orderRepository->getList(
            $this->searchCriteriaBuilder->addFilter(OrderInterface::CUSTOMER_ID, $customerId)->create()
        )->getItems();
    }

    /**
     * Get orders total amount sum.
     *
     * @param OrderInterface[] $orders
     *
     * @return int
     */
    private function getOrdersTotalAmount(array $orders): int
    {
        $amount = 0;
        foreach ($orders as $order) {
            $amount += $order->getGrandTotal();
        }

        return (int) ($amount * 100);
    }

    /**
     * Builds base object for patch request.
     *
     * @param string $orderId
     *
     * @return PatchOrderRequestInterface
     */
    public function buildPatchOrderRequestBase(string $orderId): PatchOrderRequestInterface
    {
        return $this->patchOrderRequestInterfaceFactory->create()->setOrderId($orderId);
    }

    /**
     * Build Customer object method.
     *
     * @param CartInterface $quote
     *
     * @return OrderCustomerInterface
     */
    public function buildPatchCustomerAddress(CartInterface $quote): OrderCustomerInterface
    {
        $customerRequest = $this->orderCustomerFactory->create();
        return $customerRequest
            ->setDeliveryAddress($this->buildCustomerAddress($quote->getShippingAddress()))
            ->setInvoiceAddress($this->buildCustomerAddress($quote->getBillingAddress()));
    }
}
