/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
define([
    'jquery',
    'uiLayout'
], function ($, layout) {
    return function (config) {
        require([config.url], function () {
            if (!window.hokodoSdk) {
                window.hokodoSdk = Hokodo(config.key, config.sdkConfig);
                $('body').triggerHandler('hokodoSdkResolved');
            }
        })
    }
})
