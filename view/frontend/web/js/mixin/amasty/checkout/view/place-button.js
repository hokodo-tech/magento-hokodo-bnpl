/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
define([
    'ko',
    'underscore',
    'uiRegistry',
    'Amasty_Checkout/js/model/payment/payment-loading',
    'Amasty_Checkout/js/model/address-form-state',
    'Magento_Checkout/js/model/payment/renderer-list',
    'Magento_Checkout/js/model/quote',
    'Magento_Checkout/js/model/shipping-service',
], function (
        ko,
        _,
        registry,
        paymentLoader,
        addressFormState,
        rendererList,
        quote,
        shippingService
        ) {
    'use strict'

    return function (PlaceButton) {
        return PlaceButton.extend({

            isPaymentHokodoPlaceOrderActionAllowed: ko.observable(true),

            initialize: function () {
                this._super();

                this.isPlaceOrderActionAllowed = ko.pureComputed(function () {
                    return !paymentLoader()
                            && !addressFormState.isBillingFormVisible()
                            && !addressFormState.isShippingFormVisible()
                            && !shippingService.isLoading()
                            && this.isPaymentHokodoPlaceOrderActionAllowed();
                }, this);

                quote.paymentMethod.subscribe(function (paymentMethod) {

                    if (paymentMethod && paymentMethod.method == 'hokodo_bnpl') {
                        var paymentComponentName = this.paymentsNamePrefix + quote.paymentMethod().method;
                        registry.async(paymentComponentName)(function (payment) {
                            if (payment) {

                                if (!_.isEmpty(payment.selectedPlan()) && payment.selectedPlan().id) {
                                    this.isPaymentHokodoPlaceOrderActionAllowed(true);
                                } else {
                                    this.isPaymentHokodoPlaceOrderActionAllowed(false);
                                }

                                payment.selectedPlan.subscribe(function (plan) {
                                    if (plan && plan.id) {
                                        this.isPaymentHokodoPlaceOrderActionAllowed(true);
                                    } else {
                                        this.isPaymentHokodoPlaceOrderActionAllowed(false);
                                    }
                                }, this);



                            }
                        }.bind(this));
                        return;
                    }
                    this.isPaymentHokodoPlaceOrderActionAllowed(true);

                }, this);



//    			this.isPaymentHokodoPlaceOrderActionAllowed = ko.pureComputed(function () {
//
//
//        			return true;
//        		}, this);

                return this;
            },

        });
    }
});
