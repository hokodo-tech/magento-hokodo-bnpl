/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
define([
    'mage/storage',
    'Hokodo_BNPL/js/sdk/resource-url-manager',
    'Magento_Checkout/js/model/quote',
    'Magento_Checkout/js/model/error-processor',
    'Magento_Customer/js/model/customer'
], function (
        storage,
        resourceUrlManager,
        quote,
        errorProcessor,
        customer
        ) {
    'use strict';

    return function (organisation_id, email, name) {
        var payload = {
            payload: {
                organisation_id: organisation_id,
                email: email,
                name: name
            }
        };

        return storage.post(
                resourceUrlManager.getCreateUserUrl(),
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
