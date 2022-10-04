define([
    'jquery',
    'Hokodo_BNPL/js/sdk/customer'
], function ($, customer) {
    return function (config) {
        customer().done((name) => {
            if (name !== undefined && !window.hokodoSdk) {
                require([config.url], function () {
                    let sdkConfig = {};
                    if (config.faq) {
                        sdkConfig.faqLink = config.faq
                    }
                    window.hokodoSdk = Hokodo(config.key, sdkConfig);
                    console.log('hokodo Core loaded');
                    $('body').triggerHandler('hokodoSdkResolved');
                })
            }
        })
    }
})
