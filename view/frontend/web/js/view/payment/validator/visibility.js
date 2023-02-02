/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
define([
    'Magento_Customer/js/model/customer',
    'Hokodo_BNPL/js/sdk/hokodo-data-persistor'
], function (
    customer,
    hokodoData
) {
    'use strict';
    let hideIfNoOffer = window.checkoutConfig.payment.hokodo_bnpl.hideIfNoOffer;

    return {
        isVisibilityValidationRequired() {
            return hideIfNoOffer;
        },

        validate() {
           return hokodoData.getOffer().is_eligible;
        },
    }
});
