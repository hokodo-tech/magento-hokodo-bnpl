<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Gateway\Request;

use Hokodo\BNPL\Api\Data\OrganisationInterface;
use Hokodo\BNPL\Api\Data\UserInterface;
use Hokodo\BNPL\Gateway\OrderSubjectReader;
use Hokodo\BNPL\Gateway\OrganisationSubjectReader;
use Hokodo\BNPL\Gateway\UserSubjectReader;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\ProductMetadataInterface;
use Magento\Framework\Component\ComponentRegistrar;
use Magento\Framework\Component\ComponentRegistrarInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Filesystem\Directory\ReadFactory;
use Magento\Framework\Phrase;
use Magento\Framework\Stdlib\DateTime\DateTimeFactory;
use Magento\Payment\Gateway\Request\BuilderInterface;
use Magento\Quote\Api\Data\CartInterface;
use Magento\Quote\Model\Quote;
use Magento\Quote\Model\Quote\Address;
use Magento\Quote\Model\Quote\Item;
use Magento\Store\Model\ScopeInterface;
use Magento\Tax\Model\Config as TaxConfig;
use Psr\Log\LoggerInterface as Logger;

class OrderBuilder implements BuilderInterface
{
    /**
     * @var OrderSubjectReader
     */
    private OrderSubjectReader $orderSubjectReader;

    /**
     * @var OrganisationSubjectReader
     */
    private OrganisationSubjectReader $organisationSubjectReader;

    /**
     * @var UserSubjectReader
     */
    private UserSubjectReader $userSubjectReader;

    /**
     * @var DateTimeFactory
     */
    private DateTimeFactory $dateTimeFactory;

    /**
     * @var currentTaxRate
     */
    private $currentTaxRate;

    /**
     * @var currentTax
     */
    private currentTax $currentTax;

    /**
     * @var ScopeConfigInterface
     */
    private ScopeConfigInterface $scopeConfiguration;

    /**
     * @var ProductMetadataInterface
     */
    private ProductMetadataInterface $productMetadata;

    /**
     * @var ComponentRegistrarInterface
     */
    private ComponentRegistrarInterface $componentRegistrar;

    /**
     * @var ReadFactory
     */
    private ReadFactory $readFactory;

    /**
     * @var Logger
     */
    protected Logger $logger;

    /**
     * @param OrderSubjectReader          $orderSubjectReader
     * @param OrganisationSubjectReader   $organisationSubjectReader
     * @param UserSubjectReader           $userSubjectReader
     * @param DateTimeFactory             $dateTimeFactory
     * @param ScopeConfigInterface        $scopeConfiguration
     * @param ProductMetadataInterface    $productMetadata
     * @param ComponentRegistrarInterface $componentRegistrar
     * @param ReadFactory                 $readFactory
     * @param Logger                      $logger
     */
    public function __construct(
        OrderSubjectReader $orderSubjectReader,
        OrganisationSubjectReader $organisationSubjectReader,
        UserSubjectReader $userSubjectReader,
        DateTimeFactory $dateTimeFactory,
        ScopeConfigInterface $scopeConfiguration,
        ProductMetadataInterface $productMetadata,
        ComponentRegistrarInterface $componentRegistrar,
        ReadFactory $readFactory,
        Logger $logger
    ) {
        $this->orderSubjectReader = $orderSubjectReader;
        $this->organisationSubjectReader = $organisationSubjectReader;
        $this->userSubjectReader = $userSubjectReader;
        $this->dateTimeFactory = $dateTimeFactory;
        $this->scopeConfiguration = $scopeConfiguration;
        $this->productMetadata = $productMetadata;
        $this->componentRegistrar = $componentRegistrar;
        $this->readFactory = $readFactory;
        $this->logger = $logger;
    }

    /**
     * @inheritDoc
     *
     * @see \Magento\Payment\Gateway\Request\BuilderInterface::build()
     */
    public function build(array $buildSubject)
    {
        return [
            'body' => $this->createOrderRequest($buildSubject),
        ];
    }

    /**
     * A function that create order request.
     *
     * @param array $buildSubject
     *
     * @return array
     *
     * @throws LocalizedException
     */
    private function createOrderRequest(array $buildSubject)
    {
        /* @var Quote $quote */
        $quote = $this->readCart($buildSubject);

        $request = [
            'unique_id' => 'temp-' . $quote->getId(),
            'customer' => $this->createCustomerRequest($buildSubject),
            'status' => 'draft',
            'currency' => $quote->getQuoteCurrencyCode(),
            'total_amount' => ($quote->getGrandTotal()) * 100,
            'tax_amount' => $quote->getShippingAddress()->getTaxAmount() * 100,
            'order_date' => $this->dateTimeFactory->create()->gmtDate('Y-m-d', time()),
            'items' => $this->createOrderItemsRequest($buildSubject),
            'metadata' => [
                'Magento Version: ' . $this->productMetadata->getVersion(),
                'Hokodo Module Version: ' . $this->getModuleVersion('Hokodo_BNPL'),
                'PHP version: ' . phpversion(),
            ],
        ];
        $data = [
            'payment_log_content' => $request,
            'action_title' => 'OrderBuilder: createOrderRequest',
            'status' => 1,
        ];
        $this->logger->debug(__METHOD__, $data);

        return $request;
    }

    /**
     * A function that create order items request.
     *
     * @param array $buildSubject
     *
     * @return array
     */
    private function createOrderItemsRequest(array $buildSubject): array
    {
        $quote = $this->readCart($buildSubject);
        /* @var Item[] $items */
        $items = $quote->getAllVisibleItems();
        $request = [];
        $this->currentTaxRate = false;
        foreach ($items as $item) {
            $request[] = $this->buildOrderItem($item, $item->getId());
        }
        $request[] = $this->buildOrderShipping($quote, $quote->getId() . '-shipping');
        return $request;
    }

    /**
     * A function that build order item.
     *
     * @param Item $item
     * @param int  $itemId
     *
     * @return array
     */
    private function buildOrderItem(Item $item, $itemId)
    {
        $this->currentTax = $item->getRowTotal() > 0 ?
            round($item->getRowTotalInclTax() / $item->getRowTotal(), 2) :
            $item->getRowTotalInclTax();
        //Item total calculation adjustment based on tax settings in Magento
        if ($this->isApplyTaxAdjustmen($item->getStoreId())) {
            $totalAmount = $item->getRowTotalInclTax() - $item->getDiscountAmount() * $this->currentTax;
        } else {
            $totalAmount = $item->getRowTotalInclTax() - $item->getDiscountAmount();
        }

        $this->currentTaxRate = $item->getTaxPercent();

        return [
            'item_id' => $itemId,
            'type' => 'product',
            'description' => $item->getName(),
            'metadata' => [],
            'reference' => $item->getSku(),
            'category' => '',
            'supplier_id' => '',
            'supplier_name' => '',
            'quantity' => $item->getQty(),
            'unit_price' => (int) round($totalAmount / $item->getQty() * 100),
            'tax_rate' => number_format($item->getTaxPercent(), 2),
            'total_amount' => round($totalAmount * 100),
            'tax_amount' => $item->getTaxAmount() * 100,
        ];
    }

    /**
     * A function that build order shipping.
     *
     * @param Quote  $quote
     * @param string $itemId
     *
     * @return array
     */
    private function buildOrderShipping($quote, $itemId)
    {
        $shippingAddress = $quote->getShippingAddress();
        $taxRate = ($this->currentTaxRate) ?:
            round(($shippingAddress->getShippingTaxAmount() / $shippingAddress->getShippingInclTax()) * 100, 2);
        return [
            'item_id' => $itemId,
            'type' => 'shipping',
            'description' => $shippingAddress->getShippingDescription() ?? '',
            'metadata' => [],
            'reference' => $shippingAddress->getShippingMethod(),
            'category' => '',
            'supplier_id' => '',
            'supplier_name' => '',
            'quantity' => 1,
            'unit_price' => ($shippingAddress->getShippingInclTax() -
                    $shippingAddress->getShippingDiscountAmount() * $this->currentTax) * 100,
            'tax_rate' => number_format($taxRate, 2),
            'total_amount' => ($shippingAddress->getShippingInclTax() -
                    $shippingAddress->getShippingDiscountAmount() * $this->currentTax) * 100,
            'tax_amount' => ($shippingAddress->getShippingTaxAmount() * 100),
        ];
    }

    /**
     * A function that create customer request.
     *
     * @param array $buildSubject
     *
     * @return array
     */
    private function createCustomerRequest(array $buildSubject)
    {
        $quote = $this->readCart($buildSubject);
        $customer = $quote->getCustomer();
        $organisation = $this->readOrganisation($buildSubject);
        $user = $this->readUser($buildSubject);

        return [
            'type' => $customer->getId() ? 'registered' : 'guest',
            'organisation' => $organisation->getId(),
            'user' => $user->getId(),
            'delivery_address' => $this->createDeliveryAddressRequest($buildSubject),
            'invoice_address' => $this->createInvoiceAddressRequest($buildSubject),
        ];
    }

    /**
     * A function that create delivery address request.
     *
     * @param array $buildSubject
     *
     * @return array
     */
    private function createDeliveryAddressRequest(array $buildSubject)
    {
        $quote = $this->readCart($buildSubject);
        $user = $this->readUser($buildSubject);
        /* @var Address $address */
        $address = $quote->getShippingAddress();
        return $this->addressBuilder($address, $user);
    }

    /**
     * A function that create invoice address request.
     *
     * @param array $buildSubject
     *
     * @return array
     */
    private function createInvoiceAddressRequest(array $buildSubject)
    {
        $quote = $this->readCart($buildSubject);
        $user = $this->readUser($buildSubject);
        /* @var Address $address */
        $address = $quote->getBillingAddress();
        return $this->addressBuilder($address, $user);
    }

    /**
     * A function that read cart.
     *
     * @param array $buildSubject
     *
     * @return CartInterface
     */
    private function readCart(array $buildSubject)
    {
        return $this->orderSubjectReader->readCart($buildSubject);
    }

    /**
     * A function that read user.
     *
     * @param array $buildSubject
     *
     * @return UserInterface
     */
    private function readUser(array $buildSubject)
    {
        return $this->userSubjectReader->readUser($buildSubject);
    }

    /**
     * A function that read organisation.
     *
     * @param array $buildSubject
     *
     * @return OrganisationInterface
     */
    private function readOrganisation(array $buildSubject)
    {
        return $this->organisationSubjectReader->readOrganisation($buildSubject);
    }

    /**
     * Format address into an array.
     *
     * @param Address       $address
     * @param UserInterface $user
     *
     * @return array
     */
    private function addressBuilder(Address $address, UserInterface $user): array
    {
        return [
            'name' => $address->getName(),
            'company_name' => $address->getCompany() ?? '',
            'address_line1' => $address->getStreetLine(1),
            'address_line2' => $address->getStreetLine(2),
            'address_line3' => $address->getStreetLine(3),
            'city' => $address->getCity(),
            'region' => $address->getRegion() ?? '',
            'postcode' => $address->getPostcode() ?? '',
            'country' => $address->getCountry(),
            'phone' => $address->getTelephone(),
            'email' => $address->getEmail() ?? $user->getEmail(),
        ];
    }

    /**
     * Whether tax adjustment is necessary.
     *
     * @param int $storeId
     *
     * @return bool
     */
    private function isApplyTaxAdjustmen($storeId = 0)
    {
        return $this->scopeConfiguration->getValue(
            TaxConfig::CONFIG_XML_PATH_APPLY_AFTER_DISCOUNT,
            ScopeInterface::SCOPE_STORE,
            $storeId
        ) &&
            !$this->scopeConfiguration->getValue(
                TaxConfig::CONFIG_XML_PATH_PRICE_INCLUDES_TAX,
                ScopeInterface::SCOPE_STORE,
                $storeId
            );
    }

    /**
     * Get module composer version.
     *
     * @param string $moduleName
     *
     * @return Phrase|string|void
     */
    public function getModuleVersion($moduleName)
    {
        $path = $this->componentRegistrar->getPath(
            ComponentRegistrar::MODULE,
            $moduleName
        );
        $directoryRead = $this->readFactory->create($path);
        $composerJsonData = $directoryRead->readFile('composer.json');
        $data = json_decode($composerJsonData);

        return !empty($data->version) ? $data->version : __('Read error!');
    }
}
