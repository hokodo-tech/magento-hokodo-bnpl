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
                );
    };
});
