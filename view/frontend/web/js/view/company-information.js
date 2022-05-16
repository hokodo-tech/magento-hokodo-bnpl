/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
define([
    'ko',
    'underscore',
    'Magento_Ui/js/form/form',
    'Magento_Customer/js/model/customer',
    'Magento_Customer/js/model/address-list',
    'Magento_Checkout/js/model/quote',
    'Magento_Checkout/js/action/create-billing-address',
    'Magento_Checkout/js/action/select-billing-address',
    'Magento_Checkout/js/checkout-data',
    'Magento_Checkout/js/model/checkout-data-resolver',
    'Magento_Customer/js/customer-data',
    'Magento_Checkout/js/action/set-billing-address',
    'Magento_Ui/js/model/messageList',
    'mage/translate',
    'Magento_Checkout/js/model/billing-address-postcode-validator'
], function (
        ko,
        _,
        Component,
        customer,
        addressList,
        quote,
        createBillingAddress,
        selectBillingAddress,
        checkoutData,
        checkoutDataResolver,
        customerData,
        setBillingAddressAction,
        globalMessageList,
        $t,
        billingAddressPostcodeValidator
        ) {
    'use strict';

    return Component.extend({
        defaults: {
            template: 'Hokodo_BNPL/company-information',
            visible: false,
//            actionsTemplate: 'Magento_Checkout/billing-address/actions',
//            formTemplate: 'Magento_Checkout/billing-address/form',
//            detailsTemplate: 'Magento_Checkout/billing-address/details',
            links: {
                //isAddressFormVisible: '${$.billingAddressListProvider}:isNewAddressSelected'
                selectedCountry: '${$.parentName}.hokodo_bnpl:selectedCountry',
            },
            tracks: {
                visible: true
            }
        },

        currentBillingAddress: quote.billingAddress,
        //currentCountryId: quote.billingAddress().countryId,
//        customerHasAddresses: addressOptions.length > 0,
//
//        /**
//         * Init component
//         */
        initialize: function () {
            this._super();
            quote.paymentMethod.subscribe(function () {
                checkoutDataResolver.resolveBillingAddress();
            }, this);
            //billingAddressPostcodeValidator.initFields(this.get('name') + '.form-fields');
        },

        initObservable: function () {

            this._super()
                    .observe({
                        selectedCountry: null
//                selectedAddress: null,
//                isAddressDetailsVisible: quote.billingAddress() != null,
//                isAddressFormVisible: !customer.isLoggedIn() || !addressOptions.length,
//                isAddressSameAsShipping: false,
//                saveInAddressBook: 1
                    });

            return this;

        },
    });

});
