/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
define(['Magento_Checkout/js/model/payment-service', 'Magento_Checkout/js/checkout-data', 'Magento_Checkout/js/action/select-payment-method', 'Hokodo_BNPL/js/sdk/hokodo-data-persistor'], function (paymentService, checkoutData, selectPaymentMethodAction, hokodoData) {
    'use strict';
    let hokodoConfig = window.checkoutConfig.payment.hokodo_bnpl;

    return function (checkoutDataResolver) {
        checkoutDataResolver.resolvePaymentMethod = function () {

            if (hokodoConfig.isActive &&
                (hokodoConfig.isDefault === '1' ||
                    (hokodoConfig.isDefault === '2' &&
                        hokodoData.getOffer() !== undefined &&
                        hokodoData.getOffer().is_eligible
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
