/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
define([
    'mage/storage',
    'Hokodo_BNPL/js/model/resource-url-manager',
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

    return function (user, messageContainer) {
        var payload = {user: user};

        return storage.post(
                resourceUrlManager.getUrlForCreateUser(),
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
