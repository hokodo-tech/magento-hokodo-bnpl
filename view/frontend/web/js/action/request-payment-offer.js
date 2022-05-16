/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
define([
    'underscore',
    'mage/storage',
    'Hokodo_BNPL/js/model/resource-url-manager',
    'Hokodo_BNPL/js/hokodo-data',
    'Magento_Checkout/js/model/quote',
    'Magento_Checkout/js/model/error-processor',
    'Magento_Customer/js/model/customer',
    'Magento_Checkout/js/model/full-screen-loader'
], function (
        _,
        storage,
        resourceUrlManager,
        hokodoData,
        quote,
        errorProcessor,
        customerModel,
        fullScreenLoader
        ) {
    'use strict';

    return function (order, messageContainer) {

        var payload = {
            order: order
        };

        jQuery('[data-ui-id=checkout-cart-validationmessages-message-error]').parent('.message-error').remove();
        return storage.post(
                resourceUrlManager.getUrlForRequestNewOffer(),
                JSON.stringify(payload),
                true,
                'application/json'
                ).done(
                function (response) {
                    response = hokodoData.rebuildErrorMessage(response, 0);
                    fullScreenLoader.stopLoader();
                    jQuery('.search-autocomplete-results').removeClass("_active");
                    jQuery('.admin__data-grid-loading-mask').hide();
                }
        ).fail(
                function (response) {
                    response = hokodoData.rebuildErrorMessage(response, 1);
                    fullScreenLoader.stopLoader();
                    errorProcessor.process(response, messageContainer);
                    fullScreenLoader.stopLoader();

                    fullScreenLoader.stopLoader();
                    jQuery('.search-autocomplete-results').removeClass("_active");
                    jQuery('.admin__data-grid-loading-mask').hide();
                }
        );
    };
});
