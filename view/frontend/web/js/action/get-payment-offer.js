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
    'Magento_Customer/js/model/customer'
], function (
        _,
        storage,
        resourceUrlManager,
        hokodoData,
        quote,
        errorProcessor,
        customerModel
        ) {
    'use strict';

    return function (offerId, messageContainer) {
        jQuery('[data-ui-id=checkout-cart-validationmessages-message-error]').parent('.message-error').remove();
        return storage.get(
                resourceUrlManager.getUrlForPaymentOffer(offerId),
                false
                ).done(
                function (response) {
                    response = hokodoData.rebuildErrorMessage(response, 0);
                }
        )
                .fail(
                        function (response) {
                            response = hokodoData.rebuildErrorMessage(response, 0);
                            errorProcessor.process(response, messageContainer);
                        }
                );
    };

});
