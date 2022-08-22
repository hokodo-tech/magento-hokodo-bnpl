/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
define([
    'mage/storage',
    'Hokodo_BNPL/js/sdk/resource-url-manager',
    'Hokodo_BNPL/js/hokodo-data',
    'Magento_Checkout/js/model/quote',
    'Magento_Checkout/js/model/error-processor',
    'Magento_Customer/js/model/customer'
], function (
        storage,
        resourceUrlManager,
        hokodoData,
        quote,
        errorProcessor,
        customer
        ) {
    'use strict';

    return function (id) {

        var payload = {
            payload: {
                company_id: id,
            }
        };
        return storage.post(
                resourceUrlManager.getCreateOrganisationUrl(),
                JSON.stringify(payload),
                true,
                'application/json'
                ).fail(
                function (response) {
                    response = hokodoData().rebuildErrorMessage(response, 1);
                    errorProcessor.process(response, messageContainer);
                }
        );
    };
});
