/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
define([
    'mage/storage',
    'Hokodo_BNPL/js/sdk/resource-url-manager'
], function (
    storage,
    resourceUrlManager
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
            resourceUrlManager.getRequestOfferUrl(),
            JSON.stringify(payload),
            true,
            'application/json'
        );
    };

});
