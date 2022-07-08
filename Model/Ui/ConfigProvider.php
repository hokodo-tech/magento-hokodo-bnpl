<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Hokodo\BNPL\Model\Ui;

use Hokodo\BNPL\Api\Data\HokodoOrganisationInterface;
use Hokodo\BNPL\Api\Data\OrderInformationInterface;
use Hokodo\BNPL\Api\Data\PaymentQuoteInterface;
use Hokodo\BNPL\Api\Data\UserInterface;
use Hokodo\BNPL\Api\Data\UserInterfaceFactory;
use Hokodo\BNPL\Api\HokodoOrganisationRepositoryInterface;
use Hokodo\BNPL\Api\PaymentQuoteRepositoryInterface;
use Hokodo\BNPL\Gateway\Config\Config;
use Hokodo\BNPL\Model\SaveLog as Logger;
use Magento\Checkout\Model\ConfigProviderInterface;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Customer\Api\CustomerRepositoryInterface as CustomerRepository;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Customer\Model\Context as CustomerContext;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Http\Context as HttpContext;
use Magento\Framework\App\ProductMetadataInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class Hokodo\BNPL\Model\Ui\ConfigProvider.
 */
class ConfigProvider implements ConfigProviderInterface
{
    /**
     * @var UserInterfaceFactory
     */
    private $userFactory;

    /**
     * @var HokodoOrganisationRepositoryInterface
     */
    private $organisationRepository;

    /**
     * @var PaymentQuoteRepositoryInterface
     */
    private $paymentQuoteRepository;

    /**
     * @var Config
     */
    private $config;

    /**
     * @var Logger
     */
    private $logger;

    /**
     * @var CheckoutSession
     */
    private $checkoutSession;

    /**
     * @var CustomerRepository
     */
    private $customerRepository;

    /**
     * @var CustomerSession
     */
    private $customerSession;

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var DataObjectHelper
     */
    private $dataObjectHelper;

    /**
     * @var HttpContext
     */
    private $httpContext;

    /**
     * @var ProductMetadataInterface
     */
    private $productMetadata;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @param UserInterfaceFactory                  $userFactory
     * @param HokodoOrganisationRepositoryInterface $organisationRepository
     * @param PaymentQuoteRepositoryInterface       $paymentQuoteRepository
     * @param Config                                $config
     * @param Logger                                $logger
     * @param CheckoutSession                       $checkoutSession
     * @param CustomerRepository                    $customerRepository
     * @param CustomerSession                       $customerSession
     * @param ScopeConfigInterface                  $scopeConfig
     * @param DataObjectHelper                      $dataObjectHelper
     * @param HttpContext                           $httpContext
     * @param ProductMetadataInterface              $productMetadata
     * @param StoreManagerInterface                 $storeManager
     * @param RequestInterface                      $request
     */
    public function __construct(
        UserInterfaceFactory $userFactory,
        HokodoOrganisationRepositoryInterface $organisationRepository,
        PaymentQuoteRepositoryInterface $paymentQuoteRepository,
        Config $config,
        Logger $logger,
        CheckoutSession $checkoutSession,
        CustomerRepository $customerRepository,
        CustomerSession $customerSession,
        ScopeConfigInterface $scopeConfig,
        DataObjectHelper $dataObjectHelper,
        HttpContext $httpContext,
        ProductMetadataInterface $productMetadata,
        StoreManagerInterface $storeManager,
        RequestInterface $request
    ) {
        $this->userFactory = $userFactory;
        $this->organisationRepository = $organisationRepository;
        $this->paymentQuoteRepository = $paymentQuoteRepository;
        $this->config = $config;
        $this->logger = $logger;
        $this->checkoutSession = $checkoutSession;
        $this->customerRepository = $customerRepository;
        $this->customerSession = $customerSession;
        $this->scopeConfig = $scopeConfig;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->httpContext = $httpContext;
        $this->productMetadata = $productMetadata;
        $this->storeManager = $storeManager;
        $this->request = $request;
    }

    /**
     * Retrieve assoc array of checkout configuration.
     *
     * @return array
     *
     * @throws NoSuchEntityException
     */
    public function getConfig()
    {
        $config = [
            'isActive' => $this->config->isActive(),
        ];

        if ($this->config->isAllowSpecific()) {
            $config['specificcountry'] = explode(',', $this->config->getSpecificCountry());
        }
        $config['user'] = $this->getUser();
        $config['organisation'] = $this->getOrganisation();
        $config['order'] = $this->getOrder();
        $config['version'] = $this->productMetadata->getVersion();
        $config['replace_place_order_hooks'] = $this->config->getReplacePlaceOrderHooksConfig();
        $versionNumericals = explode('.', $this->productMetadata->getVersion());

        $versionNumber = (int) preg_replace('~\D+~', '', $versionNumericals[0]) * 100
            + (int) preg_replace('~\D+~', '', $versionNumericals[1]) * 10
            + (int) preg_replace('~\D+~', '', $versionNumericals[2]);
        $config['magentoVersion'] = $versionNumber;
        $config['isDefault'] = (bool) $this->scopeConfig->getValue(
            Config::IS_PAYMENT_DEFAULT_PATH,
            ScopeInterface::SCOPE_STORE,
            $this->storeManager->getStore()->getCode()
        );
        if ($this->request->getParam('payment_method') == Config::CODE) {
            $config['isDefault'] = true;
        }

        return [
            'payment' => [
                Config::CODE => $config,
            ],
        ];
    }

    /**
     * A function that returns an array with created user.
     *
     * @return array
     */
    private function getUser()
    {
        $user = $this->userFactory->create();

        if ($this->isCustomerLoggedIn()) {
            $data = $this->getCustomerUserData();
        } else {
            $data = [
                UserInterface::ID => '',
                UserInterface::EMAIL => '',
                UserInterface::EMAIL_VALIDATED => false,
                UserInterface::UNIQUE_ID => '',
                UserInterface::NAME => '',
                UserInterface::PHONE => '',
                UserInterface::REGISTERED => '',
                UserInterface::ORGANISATIONS => [],
            ];
        }

        $this->dataObjectHelper->populateWithArray($user, $data, UserInterface::class);

        return $user->__toArray();
    }

    /**
     * A function that checks a customer is logged in.
     *
     * @return bool
     */
    private function isCustomerLoggedIn()
    {
        return (bool) $this->httpContext->getValue(CustomerContext::CONTEXT_AUTH);
    }

    /**
     * A function that returns an array with created customer.
     *
     * @return array
     *
     * @throws NoSuchEntityException
     */
    private function getCustomerUserData()
    {
        $customer = $this->getCustomer();

        $data = [
            UserInterface::EMAIL => $customer->getEmail(),
            UserInterface::UNIQUE_ID => $customer->getId(),
            UserInterface::NAME => $customer->getFirstname() . ' ' . $customer->getLastname(),
            UserInterface::PHONE => '',
            UserInterface::REGISTERED => $customer->getCreatedAt(),
            UserInterface::ORGANISATIONS => [],
        ];

        $data[UserInterface::EMAIL_VALIDATED] =
            $this->scopeConfig->isSetFlag('customer/create_account/confirm')
            && $customer->getConfirmation() === null;

        $hokodoUserIdCustomAttribute = $customer->getCustomAttribute('hokodo_user_id');
        if ($hokodoUserIdCustomAttribute && $hokodoUserIdCustomAttribute->getValue()) {
            $data[UserInterface::ID] = $hokodoUserIdCustomAttribute->getValue();
        }

        $hokodoOrganizationIdCustomAttribute = $customer->getCustomAttribute('hokodo_organization_id');
        if ($hokodoOrganizationIdCustomAttribute && $hokodoOrganizationIdCustomAttribute->getValue()) {
            $organisation = $this->organisationRepository->getById($hokodoOrganizationIdCustomAttribute->getValue());
            if ($organisation->getId() && $organisation->getApiId()) {
                $data[UserInterface::ORGANISATIONS][] = [
                    'id' => $organisation->getApiId(),
                ];
            }
        }

        return $data;
    }

    /**
     * A function that returns a customer.
     *
     * @return CustomerInterface
     */
    private function getCustomer(): CustomerInterface
    {
        return $this->customerRepository->getById($this->customerSession->getCustomerId());
    }

    /**
     * A function that returns an array with organisations.
     *
     * @return array
     *
     * @throws NoSuchEntityException
     */
    private function getOrganisation()
    {
        if ($this->isCustomerLoggedIn()) {
            $customer = $this->getCustomer();
            $hokodoOrganizationIdCustomAttribute = $customer->getCustomAttribute('hokodo_organization_id');
            if ($hokodoOrganizationIdCustomAttribute && $hokodoOrganizationIdCustomAttribute->getValue()) {
                $organisation = $this->organisationRepository->getById(
                    $hokodoOrganizationIdCustomAttribute->getValue()
                );
                return $organisation->getData();
            }
        }

        return [
            HokodoOrganisationInterface::ORGANISATION_ID => '',
            HokodoOrganisationInterface::API_ID => '',
            HokodoOrganisationInterface::COUNTRY => '',
            HokodoOrganisationInterface::NAME => '',
            HokodoOrganisationInterface::ADDRESS => '',
            HokodoOrganisationInterface::CITY => '',
            HokodoOrganisationInterface::POSTCODE => '',
            HokodoOrganisationInterface::EMAIL => '',
            HokodoOrganisationInterface::PHONE => '',
            HokodoOrganisationInterface::COMPANY_API_ID => '',
            HokodoOrganisationInterface::CREATED_AT => '',
        ];
    }

    /**
     * A function that returns an array with orders.
     *
     * @return array
     */
    private function getOrder()
    {
        $order = [
            OrderInformationInterface::ID => '',
            OrderInformationInterface::PAYMENT_OFFER => '',
        ];

        if ($this->checkoutSession->getQuote()->getId()) {
            $paymentQuote = $this->getPaymentQuote($this->checkoutSession->getQuote()->getId());
            if ($paymentQuote && $paymentQuote->getId()) {
                $order[OrderInformationInterface::ID] = $paymentQuote->getOrderId();
                $order[OrderInformationInterface::PAYMENT_OFFER] = $paymentQuote->getOfferId();
            }
        }

        return $order;
    }

    /**
     * A function that returns quote payments.
     *
     * @param int $quoteId
     *
     * @return PaymentQuoteInterface|null
     */
    private function getPaymentQuote($quoteId)
    {
        try {
            return $this->paymentQuoteRepository->getByQuoteId($quoteId);
        } catch (\Exception $e) {
            $data = [
                'payment_log_content' => $e->getMessage(),
                'action_title' => 'CartSaveBeforeObserver::execute Exception',
                'status' => 0,
                'quote_id' => $quoteId,
            ];
            $this->logger->execute($data);
            return null;
        }
    }
}
