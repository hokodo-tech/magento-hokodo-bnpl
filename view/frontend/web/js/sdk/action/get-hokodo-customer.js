/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
define([
    'mage/storage'
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
                'rest/V1/hokodo/customer',
                JSON.stringify(payload),
                true,
                'application/json'
                );
    };
});
