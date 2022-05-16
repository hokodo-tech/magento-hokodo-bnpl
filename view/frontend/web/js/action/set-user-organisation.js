/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
define([
    'mage/storage',
    'Hokodo_BNPL/js/model/resource-url-manager',
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

    return function (user, organisation, messageContainer) {

        var payload = {
            user: user,
            organisation: organisation
        };

        delete payload.organisation.isVisited;
        delete payload.organisation.level;
/*
   var storage = JSON.parse(window.localStorage["mage-cache-storage"]);
           var user = storage["checkout-data"]["shippingAddressFromData"];
            payload.user._latestValue = user;
            payload.user._latestValue.email = storage["checkout-data"]["inputFieldEmailValue"];
            payload.user._latestValue.id = false;
            console.log(payload.user);*/
        return storage.post(
                resourceUrlManager.getUrlForSetOrganisation(),
                JSON.stringify(payload),
                true,
                'application/json'
                ).fail(
                function (response) {
                    response = hokodoData.rebuildErrorMessage(response, 1);
                    errorProcessor.process(response, messageContainer);
                }
        );
    };
});
