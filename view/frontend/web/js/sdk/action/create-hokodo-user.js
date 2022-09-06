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
                );
    };

});
