<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Hokodo\BNPL\Plugin\Block\Checkout;

use Hokodo\BNPL\Gateway\Config\Config;
use Hokodo\BNPL\Model\SaveLog as PaymentLogger;
use Magento\Checkout\Block\Checkout\LayoutProcessor as MageLayoutProcessor;

/**
 * Class Hokodo\BNPL\Plugin\Block\Checkout\LayoutProcessor.
 */
class LayoutProcessor
{
    /**
     * @var Config
     */
    private $config;

    /**
     * @var PaymentLogger
     */
    protected $paymentLogger;

    /**
     * A construct.
     *
     * @param Config        $config
     * @param PaymentLogger $paymentLogger
     */
    public function __construct(
        Config $config,
        PaymentLogger $paymentLogger
    ) {
        $this->config = $config;
        $this->paymentLogger = $paymentLogger;
    }

    /**
     * A function that returns js Layout.
     *
     * @param MageLayoutProcessor $subject
     * @param array               $jsLayout
     *
     * @return array
     *
     * @throws \Exception
     */
    public function afterProcess(MageLayoutProcessor $subject, $jsLayout)
    {
        if ($this->config->isActive()) {
            $this->processHokodoBillingAddress($jsLayout);
        }
        return $jsLayout;
    }

    /**
     * A function that make process hokodo billing address.
     *
     * @param array $jsLayout
     *
     * @return void
     *
     * @throws \Exception
     */
    private function processHokodoBillingAddress(&$jsLayout)
    {
        $log = '**';
        if (isset(
            $jsLayout['components']['checkout']['children']['steps']['children']['billing-step']['children']['payment']
                ['children']['payments-list']['children']['hokodo_bnpl-form']
        )) {
            $log .= 'if';
            $data = [
                'payment_log_content' => $log,
                'action_title' => 'LayoutProcessor: processHokodoBillingAddress',
                'status' => 1,
            ];
            $this->paymentLogger->execute($data);
            $jsLayout['components']['checkout']['children']['steps']['children']['billing-step']['children']['payment']
                ['children']['payments-list']['children']['hokodo_bnpl-form']
                ['component'] = 'Hokodo_BNPL/js/view/billing-address';

            $jsLayout['components']['checkout']['children']['steps']['children']['billing-step']['children']['payment']
                ['children']['payments-list']['children']['hokodo_bnpl-form']
                ['detailsTemplate'] = 'Hokodo_BNPL/billing-address/details';

            $this->disableAddressField($jsLayout);
        }
        $this->setSearchCompanyComponents($jsLayout);
    }

    /**
     * A variable.
     *
     * @param array $jsLayout
     */
    private function setSearchCompanyComponents(&$jsLayout)
    {
        $jsLayout['components']['checkout']['children']['steps']['children']['billing-step']['children']['payment']
            ['children']['payments-list']['children']['search-country'] = $this->getCountryComponent();

        $jsLayout['components']['checkout']['children']['steps']['children']['billing-step']['children']['payment']
            ['children']['payments-list']['children']['search-company'] = $this->getCompanyComponent();
    }

    /**
     * A function that returns disables address field.
     *
     * @param array $jsLayout
     *
     * @return void
     */
    private function disableAddressField(&$jsLayout)
    {
        $jsLayout['components']['checkout']['children']['steps']['children']['billing-step']['children']['payment']
            ['children']['payments-list']['children']['hokodo_bnpl-form']
            ['children']['form-fields']['children']['company']['disabled'] = true;
        $jsLayout['components']['checkout']['children']['steps']['children']['billing-step']['children']['payment']
            ['children']['payments-list']['children']['hokodo_bnpl-form']
            ['children']['form-fields']['children']['country_id']['disabled'] = true;
    }

    /**
     * A function that returns array with country component.
     *
     * @param bool $disabled
     *
     * @return array
     */
    private function getCountryComponent($disabled = true)
    {
        return [
            'component' => 'Magento_Ui/js/form/element/select',
            'displayArea' => 'company-information-country',
            'config' => [
                'customScope' => 'billingAddresshokodo_bnpl',
                'template' => 'ui/form/element/select',
            ],
            'dataScope' => 'hokodoOrganisation.country',
            'label' => __('Country'),
            'provider' => 'checkoutProvider',
            'validation' => [
                'required-entry' => true,
            ],
            'filterBy' => null,
            'customEntry' => null,
            'visible' => true,
            'disabled' => $disabled,
            'deps' => [
                'checkoutProvider',
            ],
            'imports' => [
                'initialOptions' => 'index = checkoutProvider:dictionaries.country_id',
                'setOptions' => 'index = checkoutProvider:dictionaries.country_id',
            ],
        ];
    }

    /**
     * A function that returns an array with information about company.
     *
     * @return array
     */
    private function getCompanyComponent()
    {
        return [
            'label' => __('Search registered company'),
            'component' => 'Hokodo_BNPL/js/view/company-information/ui-select',
            'displayArea' => 'company-information-company',
            'template' => 'Hokodo_BNPL/ui-select',
            'provider' => 'checkoutProvider',
            'dataScope' => 'hokodoOrganisation',
            'countryDataScope' => 'hokodoOrganisation.country',
            'sortOrder' => 10,
        ];
    }
}
