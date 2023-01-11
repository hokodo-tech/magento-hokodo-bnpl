/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
define([
    'Magento_Checkout/js/model/payment-service',
    'Magento_Checkout/js/checkout-data',
    'Magento_Checkout/js/action/select-payment-method',
    'Hokodo_BNPL/js/sdk/hokodo-data-persistor'
], function(
    paymentService,
    checkoutData,
    selectPaymentMethodAction,
    hokodoData
) {
    'use strict';

    return function(checkoutDataResolver) {
        checkoutDataResolver.resolvePaymentMethod = function() {
            let availablePaymentMethods = paymentService.getAvailablePaymentMethods();
            let selectedPaymentMethod = checkoutData.getSelectedPaymentMethod();
            let hokodoConfig = window.checkoutConfig.payment.hokodo_bnpl;
            let defaultPayment = null;
            if (hokodoConfig.isDefault && hokodoConfig.isActive) {
                defaultPayment = hokodoConfig.paymentMethodCode;
            }
            if (typeof hokodoData.getOffer() !== 'undefined' && hokodoData.getOffer() !== ''
                && hokodoConfig.isActive && hokodoConfig.isForEligibleOrderOnly) {
                hokodoData.getOffer().offered_payment_plans.forEach(function (item, index) {
                    if (item.status === 'offered') {
                        defaultPayment = hokodoConfig.paymentMethodCode;
                    }
                })
            }
            var paymentMethod = selectedPaymentMethod ? selectedPaymentMethod : defaultPayment;

            if (availablePaymentMethods.length > 0) {
                availablePaymentMethods.some(function (payment) {
                    if (payment.method == paymentMethod) {
                        selectPaymentMethodAction(payment);
                    }
                });
            }
        };

        return checkoutDataResolver;
    };
});
