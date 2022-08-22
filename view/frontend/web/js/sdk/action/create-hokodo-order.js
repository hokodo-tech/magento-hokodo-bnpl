/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
define([
    'underscore',
    'mage/storage',
    'Hokodo_BNPL/js/sdk/resource-url-manager',
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

    return function (organisationId, userId, quoteId) {
        var payload = {
            payload: {
                user_id: userId,
                organisation_id: organisationId,
                quote_id: quoteId
            }
        };

        return storage.post(
            resourceUrlManager.getCreateOrderUrl(),
            JSON.stringify(payload),
            true,
            'application/json'
        ).fail(
            function (response) {
                errorProcessor.process(response, messageContainer);
            }
        );
    };

});
