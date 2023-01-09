/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
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

    return function (companyId) {
        var payload = {
            payload: {
                company_id: companyId
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
