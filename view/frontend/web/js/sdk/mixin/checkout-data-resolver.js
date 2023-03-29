/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
define([
    'Magento_Checkout/js/model/payment-service',
    'Magento_Checkout/js/checkout-data',
    'Magento_Checkout/js/action/select-payment-method',
    'Magento_Checkout/js/model/totals'
], function (paymentService, checkoutData, selectPaymentMethodAction, totals) {
    'use strict';
    let hokodoConfig = window.checkoutConfig.payment.hokodo_bnpl;

    return function (checkoutDataResolver) {
        checkoutDataResolver.resolvePaymentMethod = function () {

            if (hokodoConfig.isActive &&
                (hokodoConfig.isDefault === '1' ||
                    (hokodoConfig.isDefault === '2' &&
                        hokodoConfig.creditLimitThreshold &&
                        hokodoConfig.creditLimitThreshold > parseFloat(totals.getSegment('grand_total').value)
                    )
                )
            ) {
                selectPaymentMethod(hokodoConfig.paymentMethodCode);
            } else {
                selectPaymentMethod(checkoutData.getSelectedPaymentMethod());
            }
        };

        let selectPaymentMethod = function (code) {
            let availablePaymentMethods = paymentService.getAvailablePaymentMethods();

            if (availablePaymentMethods.length > 0) {
                availablePaymentMethods.some(function (payment) {
                    if (payment.method === code) {
                        selectPaymentMethodAction(payment);
                    }
                });
            }
        }

        return checkoutDataResolver;
    };
});
