define([
    'jquery',
    'uiLayout'
], function ($, layout) {
    return function (config) {
        require([config.url], function () {
            if (!window.hokodoSdk) {
                window.hokodoSdk = Hokodo(config.key, config.sdkConfig);
                layout([{
                    name: 'hokodo-sdk-marketing',
                    component: 'Hokodo_BNPL/js/sdk/marketing/marketing'
                }])
                $('body').triggerHandler('hokodoSdkResolved');
            }
        })
    }
})
