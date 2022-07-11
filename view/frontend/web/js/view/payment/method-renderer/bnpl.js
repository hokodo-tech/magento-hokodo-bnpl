/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
define([
    'jquery',
    'underscore',
    'ko',
    'uiLayout',
    'mage/translate',
    'Hokodo_BNPL/js/model/hokodo-data-service',
    'Magento_Customer/js/model/customer',
    'Magento_Checkout/js/model/quote',
    'Magento_Checkout/js/model/payment/method-list',
    'Magento_Checkout/js/action/set-payment-information',
    'Magento_Checkout/js/model/full-screen-loader',
    'Hokodo_BNPL/js/view/payment/place-order-hooks',
    'Magento_Checkout/js/view/payment/default',
    'Magento_SalesRule/js/action/set-coupon-code',
    'Magento_SalesRule/js/action/cancel-coupon',
    'Magento_Ui/js/modal/alert',
    'Magento_Customer/js/customer-data'
], function (
        $,
        _,
        ko,
        layout,
        $t,
        hokodoDataService,
        customer,
        quote,
        methodList,
        setPaymentInformation,
        fullScreenLoader,
        placeOrderHooks,
        Component,
        setCouponCodeAction,
        cancelCouponAction,
        uiAlert,
        customerData
        ) {
    'use strict';

    var paymentConfig = window.checkoutConfig.payment.hokodo_bnpl;

    // Temporarily disabling this
    // https://gitlab.com/hokodo/engineering/plugins/magento_plugin_hokodo_deferredpayment/-/issues/68
    function hasCustomerUserData()
    {
        // if (customer.isLoggedIn()) {
        //     return deepGet(
        //             customer,
        //             ['customerData', 'custom_attributes', 'hokodo_organization_id', 'value']
        //             ) > 0;
        // }
        return false;
    }

    // function deepGet(obj, path) {
    //     var length = path.length;
    //     for (var i = 0; i < length; i++) {
    //         if (obj == null)
    //             return 0;
    //         obj = obj[path[i]];
    //     }
    //     return length ? parseInt(obj) : 0;
    // }

    return Component.extend({
        defaults: {
            template: 'Hokodo_BNPL/payment/bnpl',
            listens: {
                'checkout.steps.shipping-step.shippingAddress.customer-email:email': 'resetHokodoData'
                //'isChecked': 'updateState',
            },
            modules: {
                shippingAddress: 'checkout.steps.shipping-step.shippingAddress'
            },
        },

        redirectAfterPlaceOrder: false,

        /**
         * Init component
         */
        initialize: function () {
            this._super();
            hokodoDataService.resolveHokodoData(this);

            quote.billingAddress.subscribe(function (address) {
                if (address && address.countryId) {
                    this.organisation().country = address.countryId;
                }
            }, this);

            /* Reset Order data if cart is updated during checkout */
            quote.totals.subscribe(this.resetOrderData, this);

            placeOrderHooks.afterRequestListeners.push(function () {
                if (this.isChecked() == this.getCode()) {
                    this.updateState(this.isChecked());
                }
            }.bind(this));

            setCouponCodeAction.registerSuccessCallback(this.updateDataAfterCouponCode.bind(this));
            cancelCouponAction.registerSuccessCallback(this.updateDataAfterCouponCode.bind(this));

            if (paymentConfig.isDefault && !this.methodSelected()) {
                this.selectPaymentMethod();
            }

            this.checkCustomerDataEmailChange();

            return this;
        },

        initObservable: function () {
            this._super()
                    .observe({
                        isRegisteredCompany: false,
                        isSoleTrader: false,
                        user: paymentConfig.user,
                        organisation: paymentConfig.organisation,
                        order: paymentConfig.order,
                        planList: [],
                        selectedPlan: {},
                        disabledSelection: hasCustomerUserData(),
                    });

            return this;
        },

        createMessagesComponent: function () {

            var messagesComponent = {
                parent: this.name,
                name: this.name + '.messages',
                displayArea: 'messages',
                component: 'Magento_Ui/js/view/messages',
                config: {
                    messageContainer: this.messageContainer,
                    listens: {
                        isHidden: ''
                    },
                }
            };

            layout([messagesComponent]);

            return this;
        },

        isChecked: ko.computed(function () {
            return quote.paymentMethod() ? quote.paymentMethod().method : null;
        }),

        isPlanSelected: function () {
            return !_.isEmpty(this.selectedPlan()) ? this.selectedPlan().id : null;
        },

        /**
         * Get payment method code.
         * @returns {String}
         */
        getCode: function () {
            return 'hokodo_bnpl';
        },

        /**
         * Get payment method data
         * @returns {Object}
         */
        getData: function () {
            return {
                'method': this.item.method,
                'additional_data': {
                    'hokodo_user_id': this.user().id,
                    'hokodo_organisation_id': this.organisation().api_id,
                    'hokodo_order_id': this.order().id,
                    'hokodo_payment_offer_id': this.order().payment_offer,
                }
            };
        },

        resetHokodoData: function (email) {
            if (email != this.user().email) {

                this.resetUserData(email);
                this.resetOrganisationData();
                this.resetOrderData();

                this.isRegisteredCompany(false);
                this.isSoleTrader(false);
            }
            return this;
        },

        resetUserData: function (email) {
            this.user(_.extend({}, paymentConfig.user, {email: email}));
            return this;
        },

        resetOrganisationData: function () {
            this.organisation(
                    _.extend(
                            {},
                            paymentConfig.organisation,
                            {country: quote.billingAddress().countryId}
                    )
                    );
            return this;
        },

        resetOrderData: function () {
            this.order(_.extend({}, paymentConfig.order, {id: '', payment_offer: ''}));
            this.planList([]);
            return this;
        },

        updateState: function (isChecked) {
            if (isChecked == this.getCode() && this.user().id) {
                return this.selectRegisteredCompany();
            }
            return false;
        },

        updateDataAfterCouponCode: function () {
            if (this.isChecked() == this.getCode()) {
                if (this.order().id) {
                    this.resetOrderData();
                    this.selectRegisteredCompanyAction().done(function () {
                        if (!_.isEmpty(this.isPlanSelected())) {
                            if (_.size(this.planList()) > 0) {

                                var newSelected = _.findWhere(this.planList(), {name: this.selectedPlan().name});

                                if (newSelected && newSelected.id) {
                                    this.selectedPlan(newSelected);
                                } else {
                                    this.selectedPlan({});
                                }

                            } else {
                                this.selectedPlan({});
                            }
                        }
                    }.bind(this));
                }
            }
        },

        selectRegisteredCompany: function () {
            if (!this.isRegisteredCompany()) {
                if (!this.user().email) {
                    hokodoDataService.resolveHokodoData(this);
                }

                if (!this.user().email) {
                    uiAlert({content: 'Please add email address'});
                    return false;
                }

                if (!this.shippingAddress().validateShippingInformation()) {
                    uiAlert({content: 'Please add shipping address'});
                    return false;
                }

                this.selectRegisteredCompanyAction();

                return true;
            }

            return false;
        },

        selectRegisteredCompanyAction: function () {
            return hokodoDataService.selectRegisteredCompany(this).done(this.showRegisteredCompany.bind(this));
        },

        selectSoleTrader: function () {
            this.isRegisteredCompany(false);
            this.isSoleTrader(true);
            hokodoDataService.clearData();
            return true;
            // Temporarily disabling this
            // https://gitlab.com/hokodo/engineering/plugins/magento_plugin_hokodo_deferredpayment/-/issues/68
            // if (!this.disabledSelection()) {
            //     this.isRegisteredCompany(false);
            //     this.isSoleTrader(true);
            //     hokodoDataService.clearData();
            //     return true;
            // }
            // return false;
        },

        showRegisteredCompany: function () {
            this.isRegisteredCompany(true);
            this.isSoleTrader(false);
        },

        selectPlan: function (plan) {
            this.selectedPlan(plan);
            return true;
        },

        getPaymentMessage: function () {
            // TODO: Change hardcoded locale to use the Store's current locale
            if (!_.isEmpty(this.selectedPlan()) && _.size(this.selectedPlan().scheduled_payments) === 1) {
                var scheduledPayment = this.selectedPlan().scheduled_payments[0];
                var amount = Intl.NumberFormat('en-GB', {
                    style: 'currency',
                    currency: this.selectedPlan().currency
                }).format(
                    scheduledPayment.amount / 100
                );

                var date = new Date(scheduledPayment.date).toLocaleDateString('en-GB', {
                    day: 'numeric',
                    month: 'short',
                    year: 'numeric'
                });

                return $t('Place your order today, pay ' + amount + ' on ' + date + '.');
            }
            return $t('Buy now. Pay Later.');
        },

        getPlaceOrderDeferredObject: function () {
            return $.when(
                    setPaymentInformation(this.messageContainer, this.getData())
                    );
        },

        afterPlaceOrder: function () {
            fullScreenLoader.startLoader();
            customerData.invalidate(['cart']);
            window.location.replace(this.selectedPlan().payment_url);
        },

        validate: function () {
            return this.isRegisteredCompany() && !_.isEmpty(this.selectedPlan()) && this.selectedPlan().payment_url;
        },

        methodSelected: function() {
            if (this.selectedPaymentMethod === undefined) return false;
            return this.selectedPaymentMethod();
        },

        checkCustomerDataEmailChange: function() {
            const checkout = customerData.get('checkout-data');
            this.resetHokodoData(checkout().validatedEmailValue);
        }
    });
});
