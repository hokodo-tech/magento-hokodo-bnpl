/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
define([
    'jquery',
    'underscore',
    'ko',
    'Magento_Checkout/js/view/payment/default',
    'HokodoSDK'
], function (
        $,
        _,
        ko,
        Component,
        ) {
    'use strict';

    return Component.extend({
        defaults: {
            template: 'Hokodo_BNPL/payment/bnpl-sdk',
            listens: {
                'checkout.steps.shipping-step.shippingAddress.customer-email:email': ''
            }
        },

        // TODO: Get key from backend
        hokodoElements: Hokodo("pk_test_6H224CyVnIgrsP6o5IDnFoTT-CB5YSXEvOQ8idk_QzQ").elements(),

        /**
         * Init component
         */
        initialize: function () {
            this._super();

            return this;
        },

        isChecked: ko.computed(function () {
            return quote.paymentMethod() ? quote.paymentMethod().method : null;
        }),
    });
});
